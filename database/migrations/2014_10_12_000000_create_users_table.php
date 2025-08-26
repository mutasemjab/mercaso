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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->tinyInteger('user_type');  // 1 user   // 2 wholeSale
            $table->tinyInteger('can_pay_with_receivable')->default(2);  // 1 yes   // 2 no
            $table->string('photo')->nullable();
            $table->text('fcm_token')->nullable();
            $table->tinyInteger('activate')->default(1); // 1 yes //2 no
            $table->tinyInteger('is_verified')->default(2); // 1 yes //2 no
            $table->double('points')->default(0); // 1 yes //2 no
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('business_type_id')->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();

            // Composite unique index for email and user_type
            $table->unique(['email', 'user_type']);

            // Composite unique index for phone and user_type
            $table->unique(['phone', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
