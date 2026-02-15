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
        Schema::create('supplies_orders', function (Blueprint $table) {
               $table->id();
             $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients') ->onDelete('cascade') ->onUpdate('cascade'); 
           
          
            $table->dateTime("order_date")->default(now());
            $table->text("note")->nullable();
        
          $table->unsignedTinyInteger("supply_order_status")->comment("#1 onhold  #2  accepted  #3 canceled  ");  // 1 active   2 not active 
      
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_orders');
    }
};
