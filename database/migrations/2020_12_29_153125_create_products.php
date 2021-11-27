<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode');
            $table->string('name');
            $table->longText('description');
            $table->unsignedInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('codes');
            $table->unsignedInteger('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('codes');
            $table->timestamps();
            $table->softDeletes();  
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
}
