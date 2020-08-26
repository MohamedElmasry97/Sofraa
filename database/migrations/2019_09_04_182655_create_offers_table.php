<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('cotent');
			$table->integer('price_with_offer')->unsigned();
			$table->date('from');
			$table->date('to');
			$table->integer('resturant_id')->unsigned();
			$table->integer('food_id')->unique()->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}
