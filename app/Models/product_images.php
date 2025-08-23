<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product_images extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'order'
    ];

    // Product এর সাথে relationship
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // ইমেজের full URL পেতে
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
