<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_of_manager');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('photo')->nullable();
            $table->string('address');
            $table->tinyInteger('activate')->default(1); // 1 yes // 2 no
            $table->timestamps();
        });
        DB::table('shops')->insert([
            [
                'name' => "mercaso",
                'name_of_manager' => "Ali",
                'email' => "Ali@gmail.com",
                'password' => bcrypt('123456789'), // password,
                'phone' => "0795970357",
                'address' => "shmesani",
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
        Schema::dropIfExists('shops');
    }
};
