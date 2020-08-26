<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

		$this->call('ResturantTableSeeder');
		$this->command->info('Resturant table seeded!');

		$this->call('CategoryTableSeeder');
		$this->command->info('Category table seeded!');

		$this->call('CommentTableSeeder');
		$this->command->info('Comment table seeded!');

		$this->call('FoodTableSeeder');
		$this->command->info('Food table seeded!');

		$this->call('CartTableSeeder');
		$this->command->info('Cart table seeded!');

		$this->call('OrderTableSeeder');
		$this->command->info('Order table seeded!');

		$this->call('ContactTableSeeder');
		$this->command->info('Contact table seeded!');

		$this->call('ConfigTableSeeder');
		$this->command->info('Config table seeded!');

		$this->call('CommissionTableSeeder');
		$this->command->info('Commission table seeded!');

		$this->call('CityTableSeeder');
		$this->command->info('City table seeded!');

		$this->call('NeighborhoodTableSeeder');
		$this->command->info('Neighborhood table seeded!');

		$this->call('OfferTableSeeder');
		$this->command->info('Offer table seeded!');
	}
}
