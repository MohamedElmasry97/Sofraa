<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoodsTable extends Migration {

	public function up()
	{
		Schema::create('foods', function(Blueprint $table) {
			$table->increments('id');
			$table->string('food_image')->unique()->nullable();
			$table->string('name');
			$table->text('description')->nullable();
			$table->integer('price')->unsigned();
			$table->time('timeReady');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('foods');
	}
}