<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends Migration {

	public function up()
	{
		Schema::create('config', function(Blueprint $table) {
			$table->increments('id');
			$table->string('account_bank');
			$table->string('account_bank2');
			$table->float('comission');
			$table->text('text');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('config');
	}
}
