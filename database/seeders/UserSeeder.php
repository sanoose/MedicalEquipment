<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 
  
        User::create([
            "id" => 1 ,
            'name' => ' سالم سالم ' ,
            'email' => 's', 
            'password' => Hash::make("1"),
            'phone' =>  '092901910' , 
            'location' =>   'طرابلس'  ,
            'added_by' =>  1  ,
            'block' =>  2 ,
            'role' => 1 ,  
            'profile_photo_path' =>  'images/male.png' ,  
        ]);

        Permission::create([
             "user_id" => 1  , 
             "create_supply" => 1
        ]) ;
    }
}
