<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alternative_barcode')->nullable();
            $table->unsignedInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->string('qty'); 
            $table->string('critical_qty')->nullable();    
            $table->string('price');
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
        Schema::dropIfExists('company_products');
    }
}
