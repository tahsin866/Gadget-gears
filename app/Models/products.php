<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    //

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

    // protected $casts = [
    //     'price' => 'decimal:2',
    //     'sale_price' => 'decimal:2',
    //     'featured' => 'boolean',
    // ];



     public function children()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
