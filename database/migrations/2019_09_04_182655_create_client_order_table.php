<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientOrderTable extends Migration {

	public function up()
	{
		Schema::create('client_order', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('client_id')->unique()->unsigned();
			$table->integer('order_id')->unique()->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('client_order');
	}
}