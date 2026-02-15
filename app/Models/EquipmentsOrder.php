<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentsOrder extends Model
{
        protected $guarded = [] ;  



            public function client()
                {
                        return $this->belongsTo(\App\Models\Client::class, 'client_id');
                }

                public function details()
                {
                        return $this->hasMany(\App\Models\EquipmentsOrdersDetail::class, 'equipment_order_id');
                }

                 public function scopeFilter($q, $filters)
    {
        // search => اسم المنشأة / اسم المسؤول
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->whereHas('client', function ($qq) use ($s) {
                $qq->where('entity_name', 'like', "%{$s}%")
                   ->orWhere('client_name', 'like', "%{$s}%");
            });
        }


        // equipment_order_status
        if (!empty($filters['equipment_order_status'])) {
            $q->where('equipment_order_status', (int)$filters['equipment_order_status']);
        }

        return $q;
    }

}
