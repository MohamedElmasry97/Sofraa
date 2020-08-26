<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('password');
            $table->integer('city_id')->unsigned();
            $table->integer('neighborhood_id')->unsigned();
            $table->string('api_token', 60)->unique()->nullable();
            $table->integer('pin_code')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('clients');
    }
}
