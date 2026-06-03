<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Slider: 3 event terbaru dengan gambar
        $eventsSlider = Event::published()
            ->whereNotNull('gambar_cover')
            ->latest()
            ->limit(3)
            ->get();

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
