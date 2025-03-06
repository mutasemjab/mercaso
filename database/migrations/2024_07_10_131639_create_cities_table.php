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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_fr');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
        });
        DB::table('cities')->insert([
            [

                'name_en' => "Amman",
                'name_ar' => "عمان",
                'name_fr' => "Amman",
                'country_id' => 1,
            ],
            [
                'name_en' => "Salt",
                'name_ar' => "السلط",
                'name_fr' => "Salt",
                'country_id' => 1,

            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
