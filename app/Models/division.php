<?php

namespace App\Models;

use app\Models\districs;

use Illuminate\Database\Eloquent\Model;

class division extends Model
{
    protected $table = 'division';



  public function District()
    {
        return $this->hasMany(districs::class, 'DID'); // Assuming the foreign key is 'markaz_agreement_id'
    }


    public function division()
    {
        return $this->belongsTo(division::class); // Assuming the foreign key is 'markaz_agreement_id'
    }



}
