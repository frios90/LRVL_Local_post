<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventory_id')->unsigned();            
            $table->foreign('inventory_id')->references('id')->on('inventories');
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
        Schema::dropIfExists('detail_inventories');
    }
}
