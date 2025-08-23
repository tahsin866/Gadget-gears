<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class thana extends Model
{
      protected $fillable = ['id', 'Thana_U', 'Des_ID'];



    public function District()
    {
        return $this->belongsTo(districs::class); // Assuming the foreign key is 'markaz_agreement_id'
    }
}
