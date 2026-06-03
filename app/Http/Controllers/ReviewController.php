<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventReview;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user punya tiket paid untuk event ini
        $order = Order::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['paid', 'pending'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Kamu harus memiliki tiket untuk event ini sebelum memberi ulasan.');
        }

        // Cek apakah sudah pernah review
        $existing = EventReview::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk event ini.');
        }

        EventReview::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
            'order_id' => $order->id,
            'rating'   => $request->rating,
            'ulasan'   => $request->ulasan,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }

    public function destroy(Event $event)
    {
        EventReview::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
