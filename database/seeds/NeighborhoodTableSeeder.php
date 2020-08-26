<?php

use Illuminate\Database\Seeder;
use App\Models\Neighborhood;

class NeighborhoodTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('neighborhoods')->delete();

		// 1th
		Neighborhood::create(array(
				'name' => 'el7y el2wal',
				'city_id' => 1
			));

		// 2th
		Neighborhood::create(array(
				'name' => 'Fifth Settlement',
				'city_id' => 2
			));
	}
}