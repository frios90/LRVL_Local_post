<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenseMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('license_id')->unsigned();
            $table->foreign('license_id')->references('id')->on('licenses');
            $table->unsignedInteger('menu_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus'); 
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
        Schema::dropIfExists('license_menu');
    }
}
