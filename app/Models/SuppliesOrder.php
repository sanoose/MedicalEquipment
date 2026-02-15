<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuppliesOrder extends Model
{
       protected $guarded = [] ;  

           public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function details()
    {
        return $this->hasMany(\App\Models\SuppliesOrdersDetail::class, 'supply_order_id');
    }

      public function scopeFilter($q, $filters)
    {
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->whereHas('client', function ($qq) use ($s) {
                $qq->where('entity_name', 'like', "%{$s}%")
                   ->orWhere('client_name', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['supply_order_status'])) {
            $q->where('supply_order_status', (int)$filters['supply_order_status']);
        }

        return $q;
    }
    
    
}
