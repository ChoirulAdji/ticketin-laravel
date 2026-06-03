<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLineup extends Model
{
    protected $fillable = ['event_id', 'nama', 'is_headliner', 'foto'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
