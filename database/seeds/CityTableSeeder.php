<?php

use Illuminate\Database\Seeder;
use App\Models\City;

class CityTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('cities')->delete();

		// 1th
		City::create(array(
				'name' => '6 october'
			));

		// 2th
		City::create(array(
				'name' => 'cairo'
			));
	}
}