<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Diary;

class DiaryCommentFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'diary_id'			=> Diary::factory(),
			'user_id'			=> User::factory(),
			'description'		=> $this->faker->text(100),
			'picture'			=> 0,
			'create_time'		=> time()
		];
	}
}