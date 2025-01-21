<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NameFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name_id'			=> 1,
			'name'				=> $this->faker->name(10),
			'description'		=> $this->faker->text(100),
			'first_bukva'		=> 'a',
			'gender'			=> 'm'
		];
	}
}