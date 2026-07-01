<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EoWithdrawal extends Model
{
    protected $fillable = [
        'pengelola_id',
        'amount',
        'status',
        'bank',
        'nomor_rekening',
        'nama_rekening',
        'catatan',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function pengelola()
    {
        return $this->belongsTo(User::class, 'pengelola_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
