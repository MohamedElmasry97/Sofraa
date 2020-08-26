<?php

use Illuminate\Database\Seeder;
use App\Models\Food;

class FoodTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('foods')->delete();

		// food
		Food::create(array(
				'name' => 'meat',
				'description' => 'fresh meat come from elmonofya',
				'price' => 20,
				'timeReady' => 20
			));

		// food2
		Food::create(array(
				'name' => 'burger' ,
				'description' => 'fresh meat with bread',
				'price' => 50,
				'timeReady' => 33
			));
	}
}
