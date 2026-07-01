<?php

namespace App\Http\Controllers;

use App\Models\EoApplication;
use App\Models\EoWithdrawal;
use App\Models\HeroSlider;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ── Dashboard Admin ───────────────────────────────────────────
    public function dashboard(): View
    {
        $stats = [
            'total_user'        => User::where('role','user')->count(),
            'total_eo'          => User::where('role','pengelola')->count(),
            'total_event'       => Event::count(),
            'total_pesanan'     => Order::count(),
            'total_pendapatan'  => Order::where('status','paid')->sum('total_harga'),
            'pending_eo'        => EoApplication::where('status','pending')->count(),
            'pending_event'     => Event::where('status','pending_review')->count(),
            'pending_withdrawal'=> EoWithdrawal::where('status','pending')->count(),
        ];

        // Grafik pendapatan 6 bulan
        $grafikPendapatan = Order::where('status','paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(fn($o) => $o->created_at->format('Y-m'))
            ->map(fn($g) => [
                'bulan'   => $g->first()->created_at->format('M Y'),
                'total'   => $g->sum('total_harga'),
                'pesanan' => $g->count(),
            ])
            ->sortKeys()->values();

        // EO terbaik
        $eoBest = User::where('role','pengelola')
            ->withCount('events')
            ->get()
            ->map(function($eo) {
                $eo->total_pendapatan = Order::whereHas('event', fn($q) => $q->where('pengelola_id', $eo->id))
                    ->where('status','paid')->sum('total_harga');
                return $eo;
            })
            ->sortByDesc('total_pendapatan')->take(5)->values();

        // Event terpopuler
        $eventPopuler = Event::withCount('orders')->orderByDesc('orders_count')->take(5)->get();

        // Pesanan terbaru
        $pesananTerbaru = Order::with(['user','event'])->latest()->take(10)->get();

        // Pengajuan EO pending
        $pengajuanPending = EoApplication::where('status','pending')->with('user')->latest()->take(5)->get();

        // Pie chart 1: Distribusi metode pembayaran
        $pieMetode = Order::where('status','paid')
            ->selectRaw('metode_bayar, COUNT(*) as total')
            ->groupBy('metode_bayar')
            ->pluck('total','metode_bayar');

        // Pie chart 2: Status pesanan
        $pieStatus = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status');

        // Pie chart 3: Distribusi kategori event
        $pieKategori = Event::published()
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total','kategori');

        return view('admin.dashboard', compact(
            'stats', 'grafikPendapatan', 'eoBest', 'eventPopuler', 'pesananTerbaru', 'pengajuanPending',
            'pieMetode', 'pieStatus', 'pieKategori'
        ));
    }

    // ── Manajemen User ────────────────────────────────────────────
    public function users(Request $request): View
    {
        $query = User::query();
        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) $query->where('nama_lengkap','like','%'.$request->search.'%')
                                              ->orWhere('email','like','%'.$request->search.'%');
        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleStatusUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        $user->update(['status_akun' => $user->status_akun === 'active' ? 'suspended' : 'active']);
        return back()->with('success', 'Status akun '.$user->nama_lengkap.' berhasil diubah.');
    }

    public function ubahRoleUser(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required','in:user,pengelola,admin']]);
        if ($user->id === Auth::id()) return back()->with('error', 'Tidak bisa mengubah role sendiri.');
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role '.$user->nama_lengkap.' berhasil diubah.');
    }

    public function hapusUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'Akun '.$user->nama_lengkap.' berhasil dihapus.');
    }

    // ── Manajemen Event ───────────────────────────────────────────
    public function events(Request $request): View
    {
        $query = Event::with(['pengelola'])->withCount('orders');
        // Default tampilkan pending_review dulu
        $status = $request->get('status', 'pending_review');
        $query->where('status', $status);
        if ($request->filled('search')) $query->where('judul','like','%'.$request->search.'%');
        $events = $query->latest()->paginate(20);

        $counts = [
            'pending_review' => Event::where('status','pending_review')->count(),
            'published'      => Event::where('status','published')->count(),
            'draft'          => Event::where('status','draft')->count(),
            'cancelled'      => Event::where('status','cancelled')->count(),
        ];

        return view('admin.events', compact('events', 'counts', 'status'));
    }

    public function approveEvent(Event $event): RedirectResponse
    {
        $event->update([
            'status'      => 'published',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Auto-tambah ke hero slider jika ada gambar cover
        if ($event->gambar_cover && !HeroSlider::where('event_id', $event->id)->exists()) {
            HeroSlider::create([
                'event_id' => $event->id,
                'urutan'   => HeroSlider::max('urutan') + 1,
                'aktif'    => true,
            ]);
        }

        return back()->with('success', '✅ Event "'.$event->judul.'" berhasil di-approve dan sekarang tampil ke publik.');
    }

    public function rejectEvent(Request $request, Event $event): RedirectResponse
    {
        $request->validate(['alasan' => ['nullable','string','max:500']]);
        $event->update([
            'status'         => 'draft',
            'catatan_admin'  => $request->alasan,
        ]);
        return back()->with('success', '❌ Event "'.$event->judul.'" ditolak dan dikembalikan ke EO.');
    }

    public function hapusEvent(Event $event): RedirectResponse
    {
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }

    // ── Verifikasi EO ─────────────────────────────────────────────
    public function pengajuanEo(Request $request): View
    {
        $query = EoApplication::with('user');
        if ($request->filled('status')) $query->where('status', $request->status);
        $pengajuan = $query->latest()->paginate(20);
        return view('admin.pengajuan-eo', compact('pengajuan'));
    }

    public function approveEo(Request $request, EoApplication $app): RedirectResponse
    {
        $app->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Upgrade role user jadi pengelola
        $app->user->update(['role' => 'pengelola', 'eo_verified' => true]);

        return back()->with('success', $app->user->nama_lengkap.' berhasil disetujui sebagai EO!');
    }

    public function rejectEo(Request $request, EoApplication $app): RedirectResponse
    {
        $request->validate(['catatan_admin' => ['nullable','string','max:500']]);

        $app->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by'   => Auth::id(),
            'reviewed_at'   => now(),
        ]);

        return back()->with('success', 'Pengajuan '.$app->user->nama_lengkap.' berhasil ditolak.');
    }

    public function penarikan(Request $request): View
    {
        $query = EoWithdrawal::with(['pengelola', 'processedBy']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(20);
        return view('admin.penarikan', compact('withdrawals'));
    }

    public function approveWithdrawal(EoWithdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah tidak dapat diproses lagi.');
        }

        $withdrawal->update([
            'status'       => 'processed',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Penarikan berhasil disetujui.');
    }

    public function rejectWithdrawal(Request $request, EoWithdrawal $withdrawal): RedirectResponse
    {
        $request->validate(['catatan_admin' => ['nullable','string','max:500']]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah tidak dapat ditolak lagi.');
        }

        $withdrawal->update([
            'status'       => 'rejected',
            'catatan'      => $request->catatan_admin,
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Penarikan berhasil ditolak.');
    }

    // ── Semua Pesanan ─────────────────────────────────────────────
    public function pesanan(Request $request): View
    {
        $query = Order::with(['user','event.pengelola','items.ticketCategory'])->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $orders = $query->paginate(20);

        $stats = [
            'total'      => Order::count(),
            'paid'       => Order::where('status','paid')->count(),
            'pending'    => Order::where('status','pending')->count(),
            'cancelled'  => Order::where('status','cancelled')->count(),
            'pendapatan' => Order::where('status','paid')->sum('total_harga'),
        ];

        return view('admin.pesanan', compact('orders','stats'));
    }

    public function updateStatusPesanan(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => ['required','in:pending,paid,cancelled']]);
        $order->updateStatusWithStock($request->status);
        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
