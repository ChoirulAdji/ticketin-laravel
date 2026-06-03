<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    // Daftar semua event (server-side filter + pagination)
    public function index(Request $request): View
    {
        $query = Event::published()->with('ticketCategories');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi_kota', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Filter kategori (multiple checkbox)
        if ($request->filled('kategori')) {
            $query->whereIn('kategori', (array) $request->kategori);
        }

        // Filter kota (multiple checkbox)
        if ($request->filled('kota')) {
            $query->whereIn('lokasi_kota', (array) $request->kota);
        }

        // Sort
        match ($request->get('sort', 'terdekat')) {
            'terbaru'  => $query->orderByDesc('created_at'),
            'termurah' => $query->orderBy('harga_termurah'),
            'termahal' => $query->orderByDesc('harga_termurah'),
            default    => $query->orderBy('tanggal_waktu'),
        };

        $events      = $query->paginate(12)->withQueryString();
        $kategoris   = Event::published()->distinct()->pluck('kategori')->sort()->values();
        $kotas       = Event::published()->distinct()->pluck('lokasi_kota')->sort()->values();
        $totalEvents = Event::published()->count();

        return view('events.index', compact('events', 'kategoris', 'kotas', 'totalEvents'));
    }

    // Detail event
    public function show(Event $event): View
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load(['ticketCategories', 'lineups', 'faqs', 'pengelola', 'reviews.user', 'galleries']);

        $userReview    = null;
        $userCanReview = false;

        if (auth()->check()) {
            $userReview = $event->reviews->firstWhere('user_id', auth()->id());
            if (!$userReview) {
                $userCanReview = \App\Models\Order::where('user_id', auth()->id())
                    ->where('event_id', $event->id)
                    ->whereIn('status', ['paid', 'pending'])
                    ->exists();
            }
        }

        return view('events.show', compact('event', 'userReview', 'userCanReview'));
    }

    // AJAX real-time search
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $events = Event::published()
            ->with('ticketCategories')
            ->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                      ->orWhere('lokasi_kota', 'like', "%{$q}%")
                      ->orWhere('kategori', 'like', "%{$q}%")
                      ->orWhere('venue', 'like', "%{$q}%");
            })
            ->orderBy('tanggal_waktu')
            ->limit(6)
            ->get()
            ->map(fn($e) => [
                'id'       => $e->id,
                'judul'    => $e->judul,
                'kategori' => $e->kategori,
                'kota'     => $e->lokasi_kota,
                'venue'    => $e->venue,
                'tanggal'  => $e->tanggal_waktu?->format('d M Y'),
                'cover'    => $e->cover_url,
                'url'      => route('events.show', $e),
                'harga'    => $e->harga_termurah,
            ]);

        return response()->json($events);
    }
}
