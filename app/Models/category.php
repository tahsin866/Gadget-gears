<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'order',
        'status',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // public function parent()
    // {
    //     return $this->belongsTo(Category::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

    // Products relationship - এটি সংশোধন করুন
    // public function products()
    // {
    //     return $this->hasMany(products::class, 'category_id');
    // }





    public function primaryBrands(): HasMany
    {
        return $this->hasMany(Brand::class, 'category_id');
    }

    /**
     * All brands - এই ক্যাটেগরির সব ব্র্যান্ড (Many-to-Many)
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_category')
                    ->withTimestamps()
                    ->withPivot('is_featured');
    }

    /**
     * Parent category - যদি নেস্টেড ক্যাটেগরি হয়
     */
    public function parent()
    {
        return $this->belongsTo(parent_category::class, 'parent_id');
    }

    /**
     * Child categories - সাব-ক্যাটেগরি
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Brand owner - যদি এই ক্যাটেগরি কোনো ব্র্যান্ডের অধীনে হয়
     */
    public function brandOwner(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }




}
