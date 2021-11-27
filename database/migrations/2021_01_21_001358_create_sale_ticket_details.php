<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTicketDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_ticket_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_ticket_id')->unsigned();            
            $table->foreign('sale_ticket_id')->references('id')->on('sale_tickets');
            $table->unsignedInteger('product_company_id')->unsigned();            
            $table->foreign('product_company_id')->references('id')->on('company_products');
            $table->string('barcode');
            $table->string('name');
            $table->integer('qty')->unsigned();
            $table->integer('base')->unsigned();
            $table->integer('iva')->unsigned();
            $table->integer('total')->unsigned();
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
        Schema::dropIfExists('sale_ticket_details');
    }
}
