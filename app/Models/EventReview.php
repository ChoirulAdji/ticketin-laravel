<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventReview extends Model
{
    protected $fillable = ['event_id', 'user_id', 'order_id', 'rating', 'ulasan'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
