<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rut');           
            $table->string('email')->unique();
            $table->string('name');
            $table->integer('phone')->nullable();        
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');           
            $table->string('password');
            $table->string('address')->nullable();
            $table->unsignedInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedInteger('commune_id');
            $table->foreign('commune_id')->references('id')->on('communes');
            $table->unsignedInteger('profile_id');
            $table->foreign('profile_id')->references('id')->on('codes');                       
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
