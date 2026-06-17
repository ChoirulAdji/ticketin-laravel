<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'ticket_category_id', 'qty', 'harga_satuan'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->qty * $this->harga_satuan;
    }
}
