<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'event_id',
        'total_harga',
        'total_qty',
        'status',
        'metode_bayar',
        'ticket_summary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate kode order unik
    public static function generateCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
