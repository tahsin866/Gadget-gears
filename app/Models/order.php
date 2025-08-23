<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'status',
        'payment_method',
        'guest_name',
        'guest_email',
        'guest_phone',
        'shipping_did',
        'shipping_des_id',
        'shipping_TID',
        'shipping_postoffice',
        'notes',
        'product_attr'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class)->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getIsGuestOrderAttribute(): bool
    {
        return is_null($this->user_id);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->is_guest_order ? $this->guest_name : $this->user->name;
    }

    public function getCustomerEmailAttribute(): string
    {
        return $this->is_guest_order ? $this->guest_email : $this->user->email;
    }
}
