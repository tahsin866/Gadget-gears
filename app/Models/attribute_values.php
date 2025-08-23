<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attribute_values extends Model
{
    protected $table = 'attribute_values';
    protected $fillable = ['attribute_id', 'value'];

    public function attribute()
    {
        return $this->belongsTo(attributes::class, 'attribute_id');
    }
}
