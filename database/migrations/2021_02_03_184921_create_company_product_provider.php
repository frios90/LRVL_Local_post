<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProductProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_product_provider', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_product_id')->unsigned();
            $table->foreign('company_product_id')->references('id')->on('company_products');
            $table->unsignedInteger('provider_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->integer('provider_price');
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
        Schema::dropIfExists('company_product_provider');
    }
}
