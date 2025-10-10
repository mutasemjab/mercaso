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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->double('crv')->default(0);
            $table->string('number');
            $table->string('barcode')->unique();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en');
            $table->text('description_ar');
            $table->double('tax')->default(0);
            $table->double('selling_price_for_user');
            $table->tinyInteger('in_stock')->default(1); // 1 in stock // 2 out of stock
            $table->double('min_order_for_user');
            $table->double('min_order_for_wholesale');
            $table->tinyInteger('has_variation')->default(2); // 1 yes // 2 no
            $table->double('rating')->nullable();
            $table->double('total_rating')->nullable();
            $table->double('points')->nullable();
            $table->tinyInteger('status')->default(1); //1  active //2 not active
            $table->tinyInteger('is_favourite')->default(2); //1 yes  //2 not
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->tinyInteger('product_type')->default(3); // 1 retail // 2 whole sale // 3 both

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
        Schema::dropIfExists('products');
    }
};
