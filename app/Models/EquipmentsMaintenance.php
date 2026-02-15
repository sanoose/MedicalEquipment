<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentsMaintenance extends Model
{
        protected $guarded = [] ;   

            public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function equipment()
    {
        return $this->belongsTo(\App\Models\EquipmentsCard::class, 'equipment_id');
    }

    // Scope Filter
    public function scopeFilter($query, array $filters)
    {
        // search: يبحث في (اسم الجهة) + (اسم المسؤول) + (اسم الجهاز) + (السيريال) + (الشركة)
        if (!empty($filters['search'])) {
            $s = trim($filters['search']);

            $query->where(function ($q) use ($s) {
                $q->where('serial_number', 'like', "%{$s}%")
                  ->orWhere('manufacturer', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhereHas('client', function ($qc) use ($s) {
                      $qc->where('entity_name', 'like', "%{$s}%")
                         ->orWhere('client_name', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%");
                  })
                  ->orWhereHas('equipment', function ($qe) use ($s) {
                      $qe->where('name', 'like', "%{$s}%");
                  });
            });
        }

        // status
        if (!empty($filters['maintenance_status'])) {
            $query->where('maintenance_status', (int)$filters['maintenance_status']);
        }

        return $query;
    }

}
