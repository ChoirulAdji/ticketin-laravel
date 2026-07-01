<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoApplicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HeroSliderController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/search', [EventController::class, 'search'])->name('events.search');
Route::view('/hubungi', 'pages.hubungi')->name('hubungi');
Route::view('/tentang', 'pages.tentang')->name('tentang');

// ── Auth ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});
Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ── Profile ───────────────────────────────────────────────────────
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::post('/pesanan/{order}/batalkan', [ProfileController::class, 'batalkanPesanan'])->name('batalkan');
    Route::post('/pesanan/{order}/cek-bayar', [ProfileController::class, 'cekPembayaran'])->name('cek-bayar');
    Route::get('/pesanan/{order}/download-pdf', [ProfileController::class, 'downloadTicketPDF'])->name('download-pdf');
});

// ── Daftar EO (user biasa yang ingin jadi EO) ─────────────────────
Route::middleware('auth')->prefix('daftar-eo')->name('eo.')->group(function () {
    Route::get('/', [EoApplicationController::class, 'create'])->name('daftar');
    Route::post('/', [EoApplicationController::class, 'store'])->name('store');
    Route::get('/status', [EoApplicationController::class, 'status'])->name('status');
});

// ── Wishlist
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/wishlist', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
Route::get('/wishlist/status', [WishlistController::class, 'status'])->name('wishlist.status')->middleware('auth');

// ── Reviews
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/review', [ReviewController::class, 'store'])->name('events.review.store');
    Route::delete('/events/{event}/review', [ReviewController::class, 'destroy'])->name('events.review.destroy');
});

// ── Checkout ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/pilih-tiket', [CheckoutController::class, 'pilihTiket'])->name('events.pilih-tiket');
    Route::post('/events/{event}/keranjang', [CheckoutController::class, 'simpanKeranjang'])->name('checkout.keranjang');
    Route::get('/checkout/sukses', [CheckoutController::class, 'sukses'])->name('checkout.sukses');
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{event}/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
    Route::post('/checkout/konfirmasi/{orderCode}', function ($orderCode) {
        $order = \App\Models\Order::where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->first();
        if (!$order) {
            return response()->json(['ok' => false, 'error' => 'Order tidak ditemukan'], 404);
        }
        if ($order->status === 'pending') {
            try {
                $order->updateStatusWithStock('paid');
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'ok' => false,
                    'error' => $e->errors()['stok'][0] ?? 'Stok tiket tidak mencukupi',
                ], 422);
            }
        }
        return response()->json(['ok' => true, 'status' => $order->status]);
    })->name('checkout.konfirmasi');
});

// ── Pengelola ─────────────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\PengelolaMiddleware::class])
    ->prefix('pengelola')->name('pengelola.')->group(function () {
        Route::get('/', [PengelolaController::class, 'index'])->name('dashboard');
        Route::get('/pesanan', [PengelolaController::class, 'semuaPesanan'])->name('semua-pesanan');
        Route::get('/event/tambah', [PengelolaController::class, 'create'])->name('event.create');
        Route::post('/event', [PengelolaController::class, 'store'])->name('event.store');
        Route::get('/event/{event}/edit', [PengelolaController::class, 'edit'])->name('event.edit');
        Route::put('/event/{event}', [PengelolaController::class, 'update'])->name('event.update');
        Route::delete('/event/{event}', [PengelolaController::class, 'destroy'])->name('event.destroy');
        Route::get('/penarikan', [PengelolaController::class, 'penarikan'])->name('penarikan');
        Route::post('/penarikan', [PengelolaController::class, 'ajukanPenarikan'])->name('penarikan.store');
        Route::put('/rekening', [PengelolaController::class, 'updateRekening'])->name('rekening.update');
        // Laporan EO
        Route::get('/laporan', [LaporanController::class, 'eoIndex'])->name('laporan');
        Route::get('/laporan/export', [LaporanController::class, 'eoExport'])->name('laporan.export');

        Route::get('/event/{event}/pesanan', [PengelolaController::class, 'pesanan'])->name('event.pesanan');
        Route::put('/pesanan/{order}/status', [PengelolaController::class, 'updateStatusPesanan'])->name('pesanan.status');
    });

// ── Admin ─────────────────────────────────────────────────────────
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleStatusUser'])->name('users.toggle');
        Route::post('/users/{user}/ubah-role', [AdminController::class, 'ubahRoleUser'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'hapusUser'])->name('users.hapus');

        // Events
        Route::get('/events', [AdminController::class, 'events'])->name('events');
        Route::post('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
        Route::post('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
        // Alias agar konsisten dengan blade view
        // Hero Slider
        Route::get('/hero-slider', [HeroSliderController::class, 'index'])->name('hero-slider');
        Route::post('/hero-slider', [HeroSliderController::class, 'store'])->name('hero-slider.store');
        Route::post('/hero-slider/{slider}/toggle', [HeroSliderController::class, 'toggleAktif'])->name('hero-slider.toggle');
        Route::post('/hero-slider/urutan', [HeroSliderController::class, 'updateUrutan'])->name('hero-slider.urutan');
        Route::delete('/hero-slider/{slider}', [HeroSliderController::class, 'destroy'])->name('hero-slider.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'adminIndex'])->name('laporan');
        Route::get('/laporan/export', [LaporanController::class, 'adminExport'])->name('laporan.export');

        Route::post('/events/{event}/approve-alias', [AdminController::class, 'approveEvent'])->name('admin.events.approve');
        Route::post('/events/{event}/reject-alias', [AdminController::class, 'rejectEvent'])->name('admin.events.reject');
        Route::delete('/events/{event}/hapus', [AdminController::class, 'hapusEvent'])->name('admin.events.hapus');
        Route::delete('/events/{event}', [AdminController::class, 'hapusEvent'])->name('events.hapus');

        // Verifikasi EO
        Route::get('/pengajuan-eo', [AdminController::class, 'pengajuanEo'])->name('pengajuan-eo');
        Route::post('/pengajuan-eo/{app}/approve', [AdminController::class, 'approveEo'])->name('pengajuan-eo.approve');
        Route::post('/pengajuan-eo/{app}/reject', [AdminController::class, 'rejectEo'])->name('pengajuan-eo.reject');

        // Penarikan EO
        Route::get('/penarikan', [AdminController::class, 'penarikan'])->name('penarikan');
        Route::post('/penarikan/{withdrawal}/approve', [AdminController::class, 'approveWithdrawal'])->name('penarikan.approve');
        Route::post('/penarikan/{withdrawal}/reject', [AdminController::class, 'rejectWithdrawal'])->name('penarikan.reject');

        // Pesanan
        Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan');
        Route::put('/pesanan/{order}/status', [AdminController::class, 'updateStatusPesanan'])->name('pesanan.status');
    });
