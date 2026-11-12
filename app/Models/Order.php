<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['diproses', 'selesai', 'diambil'];

    protected $fillable = ['order_code', 'customer_name', 'customer_phone', 'total_price', 'entry_date', 'estimated_finish_date', 'status', 'notes'];
    protected $casts = ['total_price' => 'decimal:2', 'entry_date' => 'date', 'estimated_finish_date' => 'date'];

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
