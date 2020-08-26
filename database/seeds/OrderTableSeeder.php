<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('orders')->delete();

		// first order 
		Order::create(array(
				'payment' => 'cash',
				'total_price' => 100,
				'delivery_address' => '123 build 123 hell yh',
				'no_order' => 123,
				'status' => accepted ,
				'resturant_id' => 1,
				'food_id' => 1
			));
	}
}