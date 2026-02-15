<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientsEquipments extends Model
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

}
