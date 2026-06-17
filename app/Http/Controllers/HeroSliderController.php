<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HeroSlider;
use Illuminate\Http\Request;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::with('event')->orderBy('urutan')->get();
        $events  = Event::published()->orderBy('judul')->get(['id', 'judul', 'gambar_cover']);
        return view('admin.hero-slider', compact('sliders', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'   => ['nullable', 'exists:events,id'],
            'judul'      => ['nullable', 'string', 'max:100'],
            'url_tujuan' => ['nullable', 'url', 'max:255'],
            'gambar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $data = [
            'event_id'   => $request->event_id,
            'judul'      => $request->judul,
            'url_tujuan' => $request->url_tujuan,
            'urutan'     => HeroSlider::max('urutan') + 1,
            'aktif'      => true,
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sliders', 'public');
        }

        HeroSlider::create($data);
        return back()->with('success', 'Slide berhasil ditambahkan!');
    }

    public function toggleAktif(HeroSlider $slider)
    {
        $slider->update(['aktif' => !$slider->aktif]);
        return back()->with('success', 'Status slide diperbarui.');
    }

    public function updateUrutan(Request $request)
    {
        foreach ($request->urutan as $id => $urutan) {
            HeroSlider::where('id', $id)->update(['urutan' => $urutan]);
        }
        return response()->json(['ok' => true]);
    }

    public function destroy(HeroSlider $slider)
    {
        if ($slider->gambar && !str_starts_with($slider->gambar, 'http')) {
            \Storage::disk('public')->delete($slider->gambar);
        }
        $slider->delete();
        return back()->with('success', 'Slide dihapus.');
    }
}
