<?php

namespace Database\Seeders;

use App\Models\SuppliesCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliesCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            SuppliesCard::insert([
            ['name' => 'Canula'],
            ['name' => 'Mask'],
            ['name' => 'Syringe'],
            ['name' => 'Gloves'],
            ['name' => 'IV Set'],
        ]);
    }
}
