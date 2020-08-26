<?php

use Illuminate\Database\Seeder;
use App\Models\Resturant;

class ResturantTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('resturants')->delete();

		// 7mza
		Resturant::create(array(
				'name' => 'hamza',
				'email' => '111@111.111',
				'phone' => '1111111111',
				'password' => Hash::make('123'),
				'minmum_order' => 50,
				'delivery_fee' => 10,
				'status' => 'available',


			));

		// pizzaHot
		Resturant::create(array(
				'name' => 'pizza',
				'email' => '222@222.222',
				'phone' => '2222222222',
				'password' => Hash::make('123'),
				'minmum_order' => 40,
				'delivery_fee' => 5,
				'status' => 'close',
				'city_id' => 1,
				'neighborhood_id' => 1
			));
	}
}
