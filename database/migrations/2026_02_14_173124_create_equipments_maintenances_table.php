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
        Schema::create('equipments_maintenances', function (Blueprint $table) {
               $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients') ->onDelete('cascade') ->onUpdate('cascade'); 
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->foreign('equipment_id')->references('id')->on('equipments_cards') ->onDelete('cascade') ->onUpdate('cascade'); 
            $table->string("description");
            $table->string("manufacturer");
            $table->string("serial_number");
          
            $table->text("note");
            $table->unsignedTinyInteger("maintenance_status")->comment("#1 onhold  #2  accepted  #3 canceled #4 done ");  // 1 active   2 not active 
      

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments_maintenances');
    }
};
