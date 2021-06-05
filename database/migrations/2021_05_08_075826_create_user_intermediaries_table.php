<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIntermediariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_intermediaries', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->integer('server_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('user_id')->references('user_id')->on('user');
            $table->foreign('server_id')->references('server_id')->on('servers');
            

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_intermediaries');
    }
}
