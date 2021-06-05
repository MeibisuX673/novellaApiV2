<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name',25);
            $table->string('user_phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_email');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
