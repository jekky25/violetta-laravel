<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScreenFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$fakeStrName = $this->faker->numberBetween(1, 1000);
		return [
			'name'				=> $this->faker->name(10),
			'date'				=> \Carbon\Carbon::createFromDate(2000,01,01)->toDateString(),
			'path_scr'			=> $fakeStrName . '.scr',
			'path_rar'			=> $fakeStrName . '.rar',
			'path_jpg'			=> $fakeStrName . '.jpg',
			'screen_sub'		=> 1,
			'size_scr'			=> $this->faker->numberBetween(1, 1000000),
			'size_rar'			=> $this->faker->numberBetween(1, 1000000),
			'zakachka'			=> $this->faker->numberBetween(0, 10000)
		];
	}
}