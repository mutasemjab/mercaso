<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_availabilities', function (Blueprint $table) {
            $table->id();
           $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('time_from');
            $table->time('time_to');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure no overlapping time slots for same delivery and day
            $table->unique(['delivery_id', 'day_of_week', 'time_from', 'time_to'], 'delivery_avail_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_availabilities');
    }
};
