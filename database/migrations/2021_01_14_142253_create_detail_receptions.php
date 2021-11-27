<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailReceptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_receptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reception_id')->unsigned();            
            $table->foreign('reception_id')->references('id')->on('receptions');
            $table->unsignedInteger('product_company_id')->unsigned();            
            $table->foreign('product_company_id')->references('id')->on('company_products');
            $table->integer('qty')->unsigned();
            $table->softDeletes();
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
        Schema::dropIfExists('detail_receptions');
    }
}
