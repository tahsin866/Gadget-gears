<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class parent_category extends Model
{


     protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'meta_title',
        'meta_description',
    ];
}
