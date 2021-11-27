<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->integer('base');
            $table->integer('iva');
            $table->integer('total');
            $table->integer('percentage_discount')->nullable();
            $table->integer('total_with_discount')->nullable();
            $table->unsignedInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('code_id')->unsigned();
            $table->foreign('code_id')->references('id')->on('codes');
            $table->string('later_associated_document')->nullable();
            $table->unsignedInteger('step_id')->unsigned();
            $table->foreign('step_id')->references('id')->on('codes');
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
        Schema::dropIfExists('sale_tickets');
    }
}
