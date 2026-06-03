<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFaq;
use App\Models\EventLineup;
use App\Models\Order;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EventGallery;
use Illuminate\View\View;

class PengelolaController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function index(): View
    {
        $user   = Auth::user();
        $events = Event::where('pengelola_id', $user->id)
                       ->withCount('orders')
                       ->with('ticketCategories')
                       ->latest()
                       ->get();

        $totalPendapatan = Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))
                                ->where('status', 'paid')
                                ->sum('total_harga');

        $totalPesanan = Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))
                             ->count();

        return view('pengelola.dashboard', compact('events', 'totalPendapatan', 'totalPesanan'));
    }

    // ── Semua Pesanan ──────────────────────────────────────────────
    public function semuaPesanan(Request $request): View
    {
        $user   = Auth::user();
        $events = Event::where('pengelola_id', $user->id)->latest()->get();

        $query = Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))
                      ->with(['user', 'event', 'items.ticketCategory'])
                      ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $orders = $query->paginate(15);

        $stats = [
            'total'     => Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))->count(),
            'paid'      => Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))->where('status','paid')->count(),
            'pending'   => Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))->where('status','pending')->count(),
            'cancelled' => Order::whereHas('event', fn($q) => $q->where('pengelola_id', $user->id))->where('status','cancelled')->count(),
        ];

        return view('pengelola.semua-pesanan', compact('orders', 'events', 'stats'));
    }

    // ── CRUD Event ─────────────────────────────────────────────────
    public function create(): View
    {
        return view('pengelola.form-event', ['event' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEvent($request);
        $data['pengelola_id']  = Auth::id();
        $data['tanggal_waktu'] = $request->tanggal . ' ' . $request->waktu . ':00';

        // Handle cropped cover (base64 dari canvas)
        if ($request->filled('cover_cropped')) {
            $data['gambar_cover'] = $this->saveCroppedImage($request->cover_cropped, 'events/cover');
        } elseif ($request->hasFile('gambar_cover')) {
            $data['gambar_cover'] = $request->file('gambar_cover')->store('events', 'public');
        }

        $event = Event::create($data);

        $this->simpanKategoriTiket($request, $event);
        $this->simpanLineup($request, $event);
        $this->simpanFaq($request, $event);
        $this->simpanGallery($request, $event);

        return redirect()->route('pengelola.dashboard')->with('success', 'Event berhasil ditambahkan!');
    }

    public function edit(Event $event): View
    {
        $this->authorizeEvent($event);
        $event->load(['ticketCategories', 'lineups', 'faqs', 'galleries']);
        return view('pengelola.form-event', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);
        $data = $this->validateEvent($request, $event->id);
        $data['tanggal_waktu'] = $request->tanggal . ' ' . $request->waktu . ':00';

        // Handle cropped cover
        if ($request->filled('cover_cropped')) {
            if ($event->gambar_cover && !str_starts_with($event->gambar_cover, 'http')) {
                Storage::disk('public')->delete($event->gambar_cover);
            }
            $data['gambar_cover'] = $this->saveCroppedImage($request->cover_cropped, 'events/cover');
        } elseif ($request->hasFile('gambar_cover')) {
            if ($event->gambar_cover && !str_starts_with($event->gambar_cover, 'http')) {
                Storage::disk('public')->delete($event->gambar_cover);
            }
            $data['gambar_cover'] = $request->file('gambar_cover')->store('events', 'public');
        }

        $event->update($data);
        $event->ticketCategories()->delete();
        $event->lineups()->delete();
        $event->faqs()->delete();

        $this->simpanKategoriTiket($request, $event);
        $this->simpanLineup($request, $event);
        $this->simpanFaq($request, $event);
        $this->simpanGallery($request, $event);

        return redirect()->route('pengelola.dashboard')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        if ($event->gambar_cover && !str_starts_with($event->gambar_cover, 'http')) {
            Storage::disk('public')->delete($event->gambar_cover);
        }

        $event->delete();
        return redirect()->route('pengelola.dashboard')->with('success', 'Event berhasil dihapus.');
    }

    // ── Pesanan per Event ──────────────────────────────────────────
    public function pesanan(Event $event): View
    {
        $this->authorizeEvent($event);

        $orders = Order::where('event_id', $event->id)
                       ->with(['user', 'items.ticketCategory'])
                       ->latest()
                       ->paginate(20);

        $stats = [
            'total'      => Order::where('event_id', $event->id)->count(),
            'paid'       => Order::where('event_id', $event->id)->where('status','paid')->count(),
            'pending'    => Order::where('event_id', $event->id)->where('status','pending')->count(),
            'cancelled'  => Order::where('event_id', $event->id)->where('status','cancelled')->count(),
            'pendapatan' => Order::where('event_id', $event->id)->where('status','paid')->sum('total_harga'),
        ];

        return view('pengelola.pesanan', compact('event', 'orders', 'stats'));
    }

    // ── Update Status Pesanan ──────────────────────────────────────
    public function updateStatusPesanan(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeEvent($order->event);
        $request->validate(['status' => ['required', 'in:pending,paid,cancelled']]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui.');
    }

    // ── Private Helpers ────────────────────────────────────────────
    private function validateEvent(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'kategori'     => ['required', 'string', 'max:100'],
            'lokasi_kota'  => ['required', 'string', 'max:100'],
            'venue'        => ['required', 'string', 'max:255'],
            'tanggal'      => ['required', 'date'],
            'waktu'        => ['required'],
            'deskripsi'    => ['nullable', 'string'],
            'status'       => ['required', 'in:draft,published,cancelled'],
            'gambar_cover'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_cropped'     => ['nullable', 'string', 'max:8000000'], // ~6MB base64
            'gallery_cropped'   => ['nullable', 'array', 'max:8'],        // maks 8 foto
            'gallery_cropped.*' => ['nullable', 'string', 'max:8000000'],
            'gallery_keep_ids'  => ['nullable', 'array'],
            'gallery_keep_ids.*'=> ['nullable', 'integer'],
        ]);
    }

    private function simpanKategoriTiket(Request $request, Event $event): void
    {
        foreach ($request->input('nama_kategori', []) as $i => $nama) {
            if (!empty(trim($nama))) {
                TicketCategory::create([
                    'event_id'      => $event->id,
                    'nama_kategori' => $nama,
                    'harga'         => $request->input('harga', [])[$i] ?? 0,
                    'kuota'         => $request->input('kuota', [])[$i] ?? 0,
                ]);
            }
        }
    }

    private function simpanLineup(Request $request, Event $event): void
    {
        $headliners = $request->input('lineup_headliner', []);
        foreach ($request->input('lineup_nama', []) as $i => $nama) {
            if (!empty(trim($nama))) {
                EventLineup::create([
                    'event_id'     => $event->id,
                    'nama'         => $nama,
                    'is_headliner' => isset($headliners[$i]),
                ]);
            }
        }
    }

    private function simpanFaq(Request $request, Event $event): void
    {
        $jawaban = $request->input('faq_jawaban', []);
        foreach ($request->input('faq_pertanyaan', []) as $i => $p) {
            if (!empty(trim($p)) && !empty(trim($jawaban[$i] ?? ''))) {
                EventFaq::create([
                    'event_id'   => $event->id,
                    'pertanyaan' => $p,
                    'jawaban'    => $jawaban[$i],
                ]);
            }
        }
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->pengelola_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
    }

    private function saveCroppedImage(string $base64, string $folder): string
    {
        // 1. Strip data URI prefix
        $base64    = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($base64, strict: true);

        // 2. Validasi — base64 decode harus berhasil dan ukuran wajar (maks 6MB decoded)
        if ($imageData === false || strlen($imageData) > 6 * 1024 * 1024) {
            abort(422, 'File gambar tidak valid atau terlalu besar.');
        }

        // 3. Cek magic bytes — tolak file yang bukan gambar nyata
        $allowedMagic = [
            "ÿØÿ"     => 'jpg',
            "PNG

"=> 'png',
            'GIF87a'           => 'gif',
            'GIF89a'           => 'gif',
            'RIFF'             => 'webp',
        ];
        $isImage = false;
        foreach ($allowedMagic as $magic => $type) {
            if (str_starts_with($imageData, $magic)) { $isImage = true; break; }
        }
        if (!$isImage) {
            abort(422, 'Format file gambar tidak dikenali.');
        }

        // 4. Buat resource GD untuk resize
        $src = @imagecreatefromstring($imageData);
        if (!$src) {
            abort(422, 'File gambar tidak dapat diproses.');
        }

        // 5. Tentukan dimensi target
        $isGallery = str_contains($folder, 'gallery');
        $targetW   = $isGallery ? 1200 : 1200;
        $targetH   = $isGallery ? 900  : 630;

        $filename = $folder . '/' . uniqid('img_', true) . '.webp';
        $fullPath = storage_path('app/public/' . $filename);
        $dir      = dirname($fullPath);
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $dst = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, imagesx($src), imagesy($src));
        imagewebp($dst, $fullPath, 85);
        imagedestroy($src);
        imagedestroy($dst);

        return $filename;
    }

    private function simpanGallery(Request $request, Event $event): void
    {
        // Delete removed gallery items
        $keepIds = array_filter((array) $request->input('gallery_keep_ids', []));
        EventGallery::where('event_id', $event->id)
            ->when(!empty($keepIds), fn($q) => $q->whereNotIn('id', $keepIds))
            ->each(function ($g) {
                if (!str_starts_with($g->path, 'http')) {
                    Storage::disk('public')->delete($g->path);
                }
                $g->delete();
            });

        // Save new gallery images (base64 cropped)
        $croppedList = array_filter((array) $request->input('gallery_cropped', []));
        $urutan = EventGallery::where('event_id', $event->id)->max('urutan') ?? 0;
        foreach ($croppedList as $b64) {
            if (empty($b64)) continue;
            $path = $this->saveCroppedImage($b64, 'events/gallery');
            EventGallery::create([
                'event_id' => $event->id,
                'path'     => $path,
                'urutan'   => ++$urutan,
            ]);
        }
    }

}
