<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatleProvidersLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_geolocations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedInteger('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedInteger('commune_id')->unsigned();
            $table->foreign('commune_id')->references('id')->on('communes');
            $table->string('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('main')->nullable();
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
        Schema::dropIfExists('provider_geolocations');
    }
}
