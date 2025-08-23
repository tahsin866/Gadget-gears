<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attributes extends Model
{
    protected $fillable = ['name', 'type', 'product_id'];

    public function attribute_values()
    {
        return $this->hasMany(attribute_values::class, 'attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
}
