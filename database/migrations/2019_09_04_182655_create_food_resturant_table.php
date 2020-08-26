<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoodResturantTable extends Migration {

	public function up()
	{
		Schema::create('food_resturant', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('food_id')->unique()->unsigned();
			$table->integer('resturant_id')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('food_resturant');
	}
}
