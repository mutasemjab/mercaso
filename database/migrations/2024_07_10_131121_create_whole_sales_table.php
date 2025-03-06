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
        Schema::create('whole_sales', function (Blueprint $table) {
            $table->id();
            $table->string('store_license')->nullable();
            $table->string('commercial_record')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('import_license')->nullable();
            $table->string('company_type')->nullable();
            $table->string('other_company_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whole_sales');
    }
};
