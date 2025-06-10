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
    Schema::create('car_discount', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_id')->constrained()->onDelete('cascade');
        $table->foreignId('discount_id')->constrained()->onDelete('cascade');
        $table->timestamps();

        $table->unique(['car_id', 'discount_id']); // prevent duplicate entries
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_discount');
    }
};
