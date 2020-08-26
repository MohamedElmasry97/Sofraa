<?php

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('comments')->delete();

		// comment1
		Comment::create(array(
				'emoji' => 'very happy',
				'content' => 'hi seed data',
				'client_id' => 1,
				'resturant_id' => 1
			));

		// comment2
		Comment::create(array(
				'emoji' => 'sad',
				'content' => 'sad seeder',
				'client_id' => 2,
				'resturant_id' => 2
			));
	}
}