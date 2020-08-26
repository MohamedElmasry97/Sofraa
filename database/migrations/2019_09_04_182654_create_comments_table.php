<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('emoji', ['veryhappy', 'happy', 'likely', 'sad', 'verysad']);
            $table->text('content');
            $table->integer('client_id')->unsigned();
            $table->integer('resturant_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('comments');
    }
}
