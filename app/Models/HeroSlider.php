<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    protected $fillable = ['event_id', 'judul', 'gambar', 'url_tujuan', 'urutan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->gambar) {
            return str_starts_with($this->gambar, 'http')
                ? $this->gambar
                : asset('storage/' . $this->gambar);
        }
        return $this->event?->cover_url ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80';
    }

    public function getTitleAttribute(): string
    {
        return $this->judul ?? $this->event?->judul ?? '';
    }

    public function getLinkAttribute(): string
    {
        if ($this->url_tujuan) return $this->url_tujuan;
        if ($this->event_id) return route('events.show', $this->event_id);
        return '#';
    }
}
