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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('username', 100);
            $table->string('password', 225);
            $table->boolean('is_super')->default(true);
            $table->boolean('is_super_admin')->default(false);
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
           $table->timestamps();
        });

        DB::table('admins')->insert([
            [
                'name' => "Admin",
                'username'=>"admin",
                'email' => "admin@demo.com",
                'password' => bcrypt('admin'), // password
                'is_super' => true, // لكل المتاجر
                'is_super_admin' => true, // هاي عشان يقدر يضيف متاجر فقط للسوبر ادمن تعطى
                'shop_id'=>null,
            ],
            [
                'name' => "mercaso",
                'username'=>"mercaso",
                'email' => "ali@gmail.com",
                'password' => bcrypt('123456789'), // password
                'is_super' => true, // لكل المتاجر
                'is_super_admin' => false, // هاي عشان يقدر يضيف متاجر فقط للسوبر ادمن تعطى
                'shop_id'=>1,
            ]

        ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
