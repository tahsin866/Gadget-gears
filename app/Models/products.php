<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'sale_price',
        'stock',
        'category_id',
        'brand_id',
        'status',
        'featured',
        'meta_title',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(brands::class, 'brand_id');
    }

    public function images()
    {
        return $this->hasMany(product_images::class, 'product_id')->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(product_images::class, 'product_id')->where('is_primary', 1);
    }

    public function attributes()
    {
        return $this->hasMany(attributes::class, 'product_id');
    }


}
