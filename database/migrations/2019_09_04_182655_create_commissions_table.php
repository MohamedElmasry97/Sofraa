<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommissionsTable extends Migration {

	public function up()
	{
		Schema::create('commissions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('total_food_price')->unsigned();
			$table->integer('commission_application')->unsigned();
			$table->integer('commission_received')->unsigned();
			$table->integer('commission_left')->unsigned();
			$table->integer('resturant_id')->unique()->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('commissions');
	}
}