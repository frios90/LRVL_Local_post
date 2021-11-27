<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');   
            $table->unsignedInteger('provider_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('providers');        
            $table->unsignedInteger('reception_status_id')->unsigned();            
            $table->foreign('reception_status_id')->references('id')->on('codes');
            $table->unsignedInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('guide')->nullable();
            $table->date('guide_date')->nullable(); 
            $table->datetime('loaded_at')->nullable(); 
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
        Schema::dropIfExists('receptions');
    }
}
