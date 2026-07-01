<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'event_id',
        'total_harga',
        'subtotal',
        'biaya_layanan',
        'pendapatan_eo',
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

    public function updateStatusWithStock(string $status): void
    {
        DB::transaction(function () use ($status) {
            $order = self::whereKey($this->getKey())->lockForUpdate()->firstOrFail();
            $oldStatus = $order->status;

            if ($oldStatus === $status) {
                return;
            }

            $items = $order->items()->with('ticketCategory')->get();

            if ($oldStatus !== 'paid' && $status === 'paid') {
                foreach ($items as $item) {
                    $updated = TicketCategory::whereKey($item->ticket_category_id)
                        ->where('kuota', '>=', $item->qty)
                        ->decrement('kuota', $item->qty);

                    if ($updated === 0) {
                        throw ValidationException::withMessages([
                            'stok' => 'Stok tiket ' . ($item->ticketCategory->nama_kategori ?? 'ini') . ' tidak mencukupi.',
                        ]);
                    }
                }
            }

            if ($oldStatus === 'paid' && $status !== 'paid') {
                foreach ($items as $item) {
                    TicketCategory::whereKey($item->ticket_category_id)
                        ->increment('kuota', $item->qty);
                }
            }

            $order->forceFill(['status' => $status])->save();
            $this->setAttribute('status', $status);
        });
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
