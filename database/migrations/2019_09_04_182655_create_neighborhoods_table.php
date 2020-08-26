<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNeighborhoodsTable extends Migration {

	public function up()
	{
		Schema::create('neighborhoods', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->integer('city_id')->unique()->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('neighborhoods');
	}
}