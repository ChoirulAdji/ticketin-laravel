<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Toggle wishlist (AJAX)
    public function toggle(Request $request, Event $event)
    {
        $user = auth()->user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create(['user_id' => $user->id, 'event_id' => $event->id]);
            $wishlisted = true;
        }

        return response()->json([
            'wishlisted' => $wishlisted,
            'count'      => Wishlist::where('event_id', $event->id)->count(),
        ]);
    }

    // Status check — untuk inisialisasi tombol ❤️ secara batch (AJAX)
    public function status(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!auth()->check() || empty($ids)) {
            return response()->json([]);
        }

        $wishlisted = Wishlist::where('user_id', auth()->id())
            ->whereIn('event_id', $ids)
            ->pluck('event_id')
            ->toArray();

        return response()->json($wishlisted);
    }
}
