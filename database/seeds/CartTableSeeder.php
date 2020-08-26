<?php

use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('carts')->delete();

		// cart1
		Cart::create(array(
				'total_price' => 500,
				'client_id' => 1,
				'resturant_id' => 1
			));

		// cart2
		Cart::create(array(
				'total_price' => 1000,
				'client_id' => 2,
				'resturant_id' => 2
			));
	}
}