<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProductStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_product_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stock_id')->unsigned();
            $table->foreign('stock_id')->references('id')->on('codes');
            $table->unsignedInteger('company_product_id')->unsigned();
            $table->foreign('company_product_id')->references('id')->on('company_products');  
            $table->string('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_product_stocks');
    }
}
