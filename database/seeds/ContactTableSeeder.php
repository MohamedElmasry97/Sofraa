<?php

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('contact')->delete();

		// first contact
		Contact::create(array(
				'full_name' => 'adel soliman',
				'email' => '123@123.123',
				'phone' => '123123123',
				'content' => 'first content from seeder',
				'type' => Complaint
			));

		// seond content
		Contact::create(array(
				'full_name' => 'mohamed ahmed',
				'email' => '111@111.111',
				'phone' => '11111111111',
				'content' => 'this is second content from seeder ',
				'type' => Complaint
			));
	}
}