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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_fr');
            $table->string('currency');
            $table->string('sympol');
            $table->timestamps();
        });
        DB::table('countries')->insert([
            [

                'name_en' => "Jordan",
                'name_ar' => "الأردن",
                'name_fr' => "Jordan",
                'currency' => "دينار أردني",
                'sympol' => "JD",
            ],
            [
                'name_en' => "Algeria",
                'name_ar' => "الجزائر",
                'name_fr' => "Algeria",
                'currency' => "دينار جزائري",
                'sympol' => "gr",

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
        Schema::dropIfExists('countries');
    }
};
