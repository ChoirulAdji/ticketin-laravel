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
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

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
        return view('profile.edit', [
            'user' => Auth::user(),
            'eoApplication' => Auth::user()->eoApplication,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $application = $user->eoApplication;

        $rules = [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'foto_profil'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'],
        ];

        if ($application) {
            $rules = array_merge($rules, [
                'nama_organisasi'   => ['required', 'string', 'max:255'],
                'jenis_entitas'     => ['required', 'string'],
                'skala_event'       => ['required', 'string'],
                'alamat_organisasi' => ['required', 'string'],
                'no_hp_bisnis'      => ['required', 'string', 'max:20'],
                'website'           => ['nullable', 'string', 'max:255'],
                'npwp'              => ['nullable', 'string', 'max:30'],
                'bank'              => ['required', 'string', 'max:50'],
                'nomor_rekening'    => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
                'nama_rekening'     => ['required', 'string', 'max:255'],
                'dokumen_legalitas' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ]);
        }

        $validated = $request->validate($rules);

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

        if ($application) {
            $applicationData = [
                'nama_organisasi'   => $validated['nama_organisasi'],
                'jenis_entitas'     => $validated['jenis_entitas'],
                'skala_event'       => $validated['skala_event'],
                'alamat_organisasi' => $validated['alamat_organisasi'],
                'no_hp_bisnis'      => $validated['no_hp_bisnis'],
                'website'           => $validated['website'] ?? null,
                'npwp'              => $validated['npwp'] ?? null,
                'bank'              => $validated['bank'],
                'nomor_rekening'    => $validated['nomor_rekening'],
                'nama_rekening'     => $validated['nama_rekening'],
            ];

            if ($request->hasFile('dokumen_legalitas')) {
                if ($application->dokumen_legalitas) {
                    Storage::disk('public')->delete($application->dokumen_legalitas);
                }
                $applicationData['dokumen_legalitas'] = $request->file('dokumen_legalitas')->store('eo-dokumen', 'public');
            }

            $application->update($applicationData);
        }

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

        $order->updateStatusWithStock('cancelled');

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
        $order->updateStatusWithStock('paid');

        return back()->with('success', '✅ Pembayaran ' . $order->order_code . ' berhasil dikonfirmasi! Tiket tersedia di tab Tiket Saya.');
    }

    // ── Download Tiket PDF ───────────────────────────────────────
    public function downloadTicketPDF(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            return back()->with('error', 'Tiket hanya bisa diunduh setelah pembayaran lunas.');
        }

        $order->loadMissing(['user', 'event', 'items.ticketCategory']);

        if (! is_dir(storage_path('fonts'))) {
            mkdir(storage_path('fonts'), 0775, true);
        }
        $logoMarkPath = public_path('images/ticketin-mark.svg');
        $logoMarkDataUri = file_exists($logoMarkPath)
            ? 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoMarkPath))
            : null;
        $fontPaths = [
            'regular' => str_replace('\\', '/', public_path('fonts/Poppins-Regular.ttf')),
            'semibold' => str_replace('\\', '/', public_path('fonts/Poppins-SemiBold.ttf')),
            'bold' => str_replace('\\', '/', public_path('fonts/Poppins-Bold.ttf')),
        ];

        // Siapkan data tiket dengan QR code
        $tickets = [];
        $ticketIndex = 0;
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(76),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        foreach ($order->items as $item) {
            $passengers = $item->passenger_data ?? [];
            for ($unit = 1; $unit <= $item->qty; $unit++) {
                $ticketIndex++;
                $ticketCode = $order->order_code . '-' . str_pad($ticketIndex, 3, '0', STR_PAD_LEFT);
                $passenger = $passengers[$unit] ?? null;
                
                $qrSvg = $writer->writeString($ticketCode);
                
                $tickets[] = [
                    'code' => $ticketCode,
                    'category' => $item->ticketCategory->nama_kategori ?? 'Tiket',
                    'price' => 'Rp ' . number_format($item->harga_satuan, 0, ',', '.'),
                    'passenger_name' => $passenger['name'] ?? null,
                    'passenger_phone' => $passenger['phone'] ?? null,
                    'qr_data_uri' => 'data:image/svg+xml;base64,' . base64_encode($qrSvg),
                ];
            }
        }

        $html = view('profile.ticket-pdf', [
            'order' => $order,
            'tickets' => $tickets,
            'fontPaths' => $fontPaths,
            'logoMarkDataUri' => $logoMarkDataUri,
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isPhpEnabled', false)
            ->setOption('isRemoteEnabled', false)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('defaultFont', 'Poppins')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download('tiket-' . $order->order_code . '.pdf');
    }
}
