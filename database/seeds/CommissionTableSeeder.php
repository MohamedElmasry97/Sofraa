<?php

use Illuminate\Database\Seeder;
use App\Models\Commission;

class CommissionTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('commissions')->delete();

		// first
		Commission::create(array(
				'total_food_price' => 5000,
				'commission_application' => 500,
				'commission_received' => 300,
				'commission_left' => 200,
				'resturant_id' => 1
			));

		// second
		Commission::create(array(
				'total_food_price' => 1000,
				'commission_application' => 100,
				'commission_received' => 70,
				'commission_left' => 30,
				'resturant_id' => 2
			));
	}
}