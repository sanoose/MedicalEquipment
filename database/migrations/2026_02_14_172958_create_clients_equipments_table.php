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
        Schema::create('clients_equipments', function (Blueprint $table) {
              $table->id();

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients') ->onDelete('cascade') ->onUpdate('cascade'); 
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->foreign('equipment_id')->references('id')->on('equipments_cards') ->onDelete('cascade') ->onUpdate('cascade'); 
            $table->string("description");
            $table->string("manufacturer");
            $table->string("serial_number");
          
            $table->text("note");
  
      

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_equipments');
    }
};
