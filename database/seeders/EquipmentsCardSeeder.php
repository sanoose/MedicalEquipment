<?php

namespace Database\Seeders;

use App\Models\EquipmentsCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentsCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EquipmentsCard::upsert([
                ['name' => 'Ventilator'],                 // جهاز تنفس
                ['name' => 'Patient Monitor'],            // جهاز مراقبة
                ['name' => 'Defibrillator'],              // جهاز صدمات
                ['name' => 'ECG Machine'],                // جهاز تخطيط قلب
                ['name' => 'Infusion Pump'],              // مضخة محاليل
                ['name' => 'Syringe Pump'],               // مضخة حقن
                ['name' => 'Suction Machine'],            // جهاز شفط
                ['name' => 'Oxygen Concentrator'],        // مولد/مكثف أكسجين
                ['name' => 'Nebulizer'],                  // جهاز بخار
                ['name' => 'Anesthesia Machine'],         // جهاز تخدير
                ['name' => 'Autoclave'],                  // جهاز تعقيم
                ['name' => 'Ultrasound Machine'],         // جهاز سونار
                ['name' => 'X-Ray Machine'],              // جهاز أشعة
                ['name' => 'CT Scanner'],                 // جهاز CT
                ['name' => 'MRI Scanner'],                // جهاز MRI
                ['name' => 'Endoscope'],                  // منظار
                ['name' => 'Sterilizer'],                 // معقم
                ['name' => 'Hospital Bed'],               // سرير طبي
                ['name' => 'Wheelchair'],                 // كرسي متحرك
                ['name' => 'Pulse Oximeter'],             // مقياس أكسجين
            ], ['name']); 
    }
}
