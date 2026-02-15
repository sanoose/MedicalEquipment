<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
       protected $guarded = [] ;   

          public function maintenances()
    {
        return $this->hasMany(\App\Models\EquipmentsMaintenance::class, 'client_id');
    }
    
            
        public function clients_equipments()
        {
            return $this->hasMany(\App\Models\ClientsEquipments::class, 'client_id');
        }

        public function equipments_orders()
        {
            return $this->hasMany(\App\Models\EquipmentsOrder::class, 'client_id');
        }

}
