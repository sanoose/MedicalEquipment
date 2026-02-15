<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuppliesOrdersDetail extends Model
{
         protected $guarded = [] ;  


          public function order()
    {
        return $this->belongsTo(\App\Models\SuppliesOrder::class, 'supply_order_id');
    }

    public function supply()
    {
        return $this->belongsTo(\App\Models\SuppliesCard::class, 'supply_id');
    }
    
}
