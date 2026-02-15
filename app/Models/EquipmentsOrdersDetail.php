<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentsOrdersDetail extends Model
{
        protected $guarded = [] ;  


            public function order()
    {
        return $this->belongsTo(\App\Models\EquipmentsOrder::class, 'equipment_order');
    }

    public function equipment()
    {
        return $this->belongsTo(\App\Models\EquipmentsCard::class, 'equipment_id');
    }
    
}
