<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('codes');
            $table->unsignedInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');             
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
        Schema::dropIfExists('category_products');
    }
}
