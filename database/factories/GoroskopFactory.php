<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoroskopFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'gor_name'			=> $this->faker->name(10),
			'gor_type'			=> 1,
			'gor_dopoln'		=> 1,
			'gor_text'			=> $this->faker->text(100)
		];
	}
}