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
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('car_id')->constrained()->onDelete('cascade');

        $table->date('start_date');
        $table->date('end_date');
        $table->integer('days');
        $table->decimal('price_per_day', 10, 2);
        $table->decimal('total_price', 10, 2);
        $table->enum('pickup_location', ['Company Site','Queen Alia Airport','7th Circle','Mecca Street','University of Jordan','Downtown','Other'])->default('Company Site');
        $table->enum('dropoff_location', ['Company Site','Queen Alia Airport','7th Circle','Mecca Street','University of Jordan','Downtown','Other'])->default('Company Site');
       
        $table->enum('status', ['Active', 'Pending', 'Cancelled','Completed'])->default('Active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
