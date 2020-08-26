<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResturantsTable extends Migration
{
    public function up()
    {
        Schema::create('resturants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->integer('minmum_order')->unsigned();
            $table->integer('delivery_fee')->unsigned();
            $table->string('communication_phone')->unique()->nullable();
            $table->string('whats_up')->unique()->nullable();
            $table->string('resturant_image')->nullable();
            $table->enum('status', ['available', 'close']);
            $table->integer('city_id')->unique()->unsigned();
            $table->integer('neighborhood_id')->unique()->unsigned();
            $table->string('api_token', 60)->unique()->nullable();
            $table->integer('pin_code')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('resturants');
    }
}
