<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('categories')->delete();

		// chickens
		Category::create(array(
				'name' => 'chickens'
			));

		// meat
		Category::create(array(
				'name' => 'Buffalo meat'
			));
	}
}