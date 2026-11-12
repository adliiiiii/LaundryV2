<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'item_id', 'item_name', 'item_price', 'qty', 'unit', 'subtotal'];
    protected $casts = ['item_price' => 'decimal:2', 'qty' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
}
