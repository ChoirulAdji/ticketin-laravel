<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Slider: 3 event terbaru dengan gambar
        // Ambil dari hero_sliders jika ada, fallback ke event published
        $heroSliders = HeroSlider::with('event')->where('aktif', true)->orderBy('urutan')->get();
        if ($heroSliders->isNotEmpty()) {
            $eventsSlider = $heroSliders;
        } else {
            $eventsSlider = Event::published()
                ->whereNotNull('gambar_cover')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($e) => (object)[
                    'image_url'  => $e->cover_url,
                    'title'      => $e->judul,
                    'link'       => route('events.show', $e),
                    'event'      => $e,
                    'event_id'   => $e->id,
                ]);
        }

        // Event Terdekat: 4 event upcoming terdekat
        $eventsTerdekat = Event::published()
            ->upcoming()
            ->with('ticketCategories')
            ->orderBy('tanggal_waktu')
            ->limit(4)
            ->get();

        // Event Rekomendasi: 4 event terbaru
        $eventsRekomendasi = Event::published()
            ->with('ticketCategories')
            ->latest()
            ->limit(4)
            ->get();

        return view('dashboard.index', compact('eventsSlider', 'eventsTerdekat', 'eventsRekomendasi'));
    }
}
