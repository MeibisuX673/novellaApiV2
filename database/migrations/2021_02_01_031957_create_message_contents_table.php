<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->string('content');
            $table->boolean('status')->default(false);
            $table->date('date');
            $table->time('time');
            $table->integer('chat_id')->unsigned();
            $table->integer('user_id')->unsigned();


            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('message_types');

            $table->foreign('chat_id')->references('chat_id')->on('chats');

            $table->foreign('user_id')->references('user_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_contents');
    }
}
