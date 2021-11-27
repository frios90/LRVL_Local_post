<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id')->nullable();
            $table->foreign('profile_id')->references('id')->on('codes');
            $table->unsignedInteger('menu_id')->nullable();
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
        Schema::dropIfExists('menu_profile');
    }
}
