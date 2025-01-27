<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiaryFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'user_id'			=> 1,
			'title'				=> $this->faker->name(10),
			'description'		=> $this->faker->text(100),
			'picture'			=> 0,
			'create_time'		=> time()
		];
	}
}