<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'product_attr'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class);
    }

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(attribute_values::class, 'product_attr');
    }
}
