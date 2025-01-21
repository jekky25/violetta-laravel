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
			'dnevniki_user_id'	=> 1,
			'dnevniki_title'	=> $this->faker->name(10),
			'dnevniki_text'		=> $this->faker->text(100),
			'dnevniki_picture'	=> 0,
			'dnevniki_time'		=> time()
		];
	}
}