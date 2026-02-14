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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger("client_type")->comment("#1 hospital  #2 company");  // 1 active   2 not active 
            $table->unsignedTinyInteger("client_subtype")->comment("#1 hospital  #2 clinic #3  public_entity  #4 private_entity ");  // 1 active   2 not active 

            $table->string("entity_name");
            $table->string("client_name");
            $table->string("phone");
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
        Schema::dropIfExists('clients');
    }
};
