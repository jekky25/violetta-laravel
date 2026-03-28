<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HoroscopeFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name'				=> $this->faker->name(10),
			'type'				=> 1,
			'addition'			=> 1,
			'description'		=> $this->faker->text(100)
		];
	}
}