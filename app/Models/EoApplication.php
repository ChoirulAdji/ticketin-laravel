<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EoApplication extends Model
{
    protected $fillable = [
        'user_id', 'nama_organisasi', 'jenis_entitas', 'skala_event',
        'alamat_organisasi', 'website', 'npwp', 'dokumen_legalitas',
        'bank', 'nomor_rekening', 'nama_rekening', 'no_hp_bisnis',
        'status', 'catatan_admin', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getDokumenUrlAttribute(): ?string
    {
        return $this->dokumen_legalitas ? asset('storage/' . $this->dokumen_legalitas) : null;
    }
}
