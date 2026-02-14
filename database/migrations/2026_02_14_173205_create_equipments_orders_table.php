<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Symfony\Component\Clock\now;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipments_orders', function (Blueprint $table) {
               $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients') ->onDelete('cascade') ->onUpdate('cascade'); 
           
          
            $table->text("note");
           $table->dateTime('order_date')->useCurrent();
 
          $table->unsignedTinyInteger("equipment_order_status")->comment("#1 onhold  #2  accepted  #3 canceled  ");  // 1 active   2 not active 
      
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments_orders');
    }
};
