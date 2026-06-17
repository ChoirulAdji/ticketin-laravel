<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    protected $fillable = ['event_id', 'path', 'urutan'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http')) return $this->path;
        return asset('storage/' . $this->path);
    }
}
