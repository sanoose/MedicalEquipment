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
        Schema::create('supplies_orders_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supply_id')->nullable();
            $table->foreign('supply_id')->references('id')->on('supplies_cards') ->onDelete('cascade') ->onUpdate('cascade'); 
           
            $table->unsignedBigInteger('supply_order_id')->nullable();
            $table->foreign('supply_order_id')->references('id')->on('supplies_orders') ->onDelete('cascade') ->onUpdate('cascade'); 

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
        Schema::dropIfExists('supplies_orders_details');
    }
};
