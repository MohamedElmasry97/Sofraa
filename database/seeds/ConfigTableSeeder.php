<?php

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('config')->delete();

		// config1
		Config::create(array(
				'account_bank' => '123123123123213123',
				'account_bank2' => '3452423663241',
				'text' => 'please pay money for improve this application or will fuck U!! '
			));
	}
}