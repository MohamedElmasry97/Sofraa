<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactTable extends Migration {

	public function up()
	{
		Schema::create('contact', function(Blueprint $table) {
			$table->increments('id');
			$table->string('full_name');
			$table->string('email');
			$table->string('phone');
			$table->text('content');
			$table->enum('type', array('Complaint', 'Suggest', 'Enquiry'));
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('contact');
	}
}