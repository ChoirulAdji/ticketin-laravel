<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user   = Auth::user();
        $orders = $user->orders()
                       ->with(['event', 'items.ticketCategory'])
                       ->latest()
                       ->get();

        $wishlists = $user->wishlistedEvents()
                          ->with('ticketCategories')
                          ->orderByPivot('created_at', 'desc')
                          ->get();

        return view('profile.index', compact('user', 'orders', 'wishlists'));
    }

    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'foto_profil'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) Storage::disk('public')->delete($user->foto_profil);
            $file     = $request->file('foto_profil');
            $path     = 'avatars/' . uniqid('av_', true) . '.webp';
            $fullPath = storage_path('app/public/' . $path);
            $dir      = dirname($fullPath);
            if (!is_dir($dir)) mkdir($dir, 0775, true);

            // Resize avatar ke 400×400
            $src = imagecreatefromstring(file_get_contents($file->getRealPath()));
            if ($src) {
                $dst = imagecreatetruecolor(400, 400);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, 400, 400, imagesx($src), imagesy($src));
                imagewebp($dst, $fullPath, 85);
                imagedestroy($src);
                imagedestroy($dst);
                $user->foto_profil = $path;
            } else {
                $user->foto_profil = $file->store('avatars', 'public');
            }
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email        = $request->email;
        $user->no_hp        = $request->no_hp;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    // ── Batalkan Pesanan ──────────────────────────────────────────
    public function batalkanPesanan(Order $order): RedirectResponse
    {
        // Pastikan order milik user ini
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa batalkan yang masih pending
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan. Hanya pesanan dengan status menunggu yang bisa dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan ' . $order->order_code . ' berhasil dibatalkan.');
    }

    // ── Cek Pembayaran (update pending → paid) ────────────────────
    public function cekPembayaran(Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan sudah ' . $order->status . '.');
        }

        // Simulasi: update ke paid
        $order->update(['status' => 'paid']);

        return back()->with('success', '✅ Pembayaran ' . $order->order_code . ' berhasil dikonfirmasi! Tiket tersedia di tab Tiket Saya.');
    }
}
