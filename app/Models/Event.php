<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengelola_id',
        'judul',
        'kategori',
        'lokasi_kota',
        'venue',
        'tanggal_waktu',
        'deskripsi',
        'gambar_cover',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    // Relasi
    public function pengelola()
    {
        return $this->belongsTo(User::class, 'pengelola_id');
    }

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function lineups()
    {
        return $this->hasMany(EventLineup::class)->orderByDesc('is_headliner');
    }

    public function faqs()
    {
        return $this->hasMany(EventFaq::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scope: hanya event published
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope: event yang akan datang
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_waktu', '>=', now());
    }

    // Helper: harga tiket termurah
    public function getHargaTermurahAttribute()
    {
        return $this->ticketCategories()->min('harga') ?? 0;
    }


    public function galleries()
    {
        return $this->hasMany(EventGallery::class)->orderBy('urutan');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(EventReview::class)->latest();
    }

    public function getRatingRataRataAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getJumlahReviewAttribute(): int
    {
        return $this->reviews()->count();
    }
    // Helper: URL gambar cover
    public function getCoverUrlAttribute(): string
    {
        if ($this->gambar_cover) {
            if (str_starts_with($this->gambar_cover, 'http')) {
                return $this->gambar_cover;
            }
            return asset('storage/' . $this->gambar_cover);
        }
        return 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80';
    }
}
