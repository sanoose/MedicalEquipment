<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipments_orders_details', function (Blueprint $table) {
               $table->id();
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->foreign('equipment_id')->references('id')->on('equipments_cards') ->onDelete('cascade') ->onUpdate('cascade'); 
           
            $table->unsignedBigInteger('equipment_order')->nullable();
            $table->foreign('equipment_order')->references('id')->on('equipments_orders') ->onDelete('cascade') ->onUpdate('cascade'); 

            $table->double("amount")->default(1);
      

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments_orders_details');
    }
};
