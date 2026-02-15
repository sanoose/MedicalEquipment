<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentsCard extends Model
{
        protected $guarded = [] ;   

           public function maintenances()
    {
        return $this->hasMany(\App\Models\EquipmentsMaintenance::class, 'equipment_id');
    }
    

    public function clients_equipments()
{
    return $this->hasMany(\App\Models\ClientsEquipments::class, 'equipment_id');
}

public function orders_details()
{
    return $this->hasMany(\App\Models\EquipmentsOrdersDetail::class, 'equipment_id');
}

}
