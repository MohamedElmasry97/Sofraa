<?php

use Illuminate\Database\Seeder;
use App\Models\Offer;

class OfferTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('offers')->delete();

		// offer1
		Offer::create(array(
				'name' => 'burger',
				'cotent' => 'this is offer from seeder',
				'price_with_offer' => 35,
				'from' => 1-8-2019,
				'to' => 1-9-2020,
				'resturant_id' => 1,
				'food_id' => 1
			));

		// offer2
		Offer::create(array(
				'name' => 'fish',
				'cotent' => 'fish one from seeder',
				'price_with_offer' => 44,
				'from' => 4-4-2020,
				'to' => 4-4-2020,
				'resturant_id' => 2,
				'food_id' => 2
			));
	}
}