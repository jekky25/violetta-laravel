<?php

namespace Database\Factories;

use Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class GoroskopFactory extends Factory
{
	private static $goroskopId = 0;

	public function __construct($count = null,
		?Collection $states = null,
		?Collection $has = null,
		?Collection $for = null,
		?Collection $afterMaking = null,
		?Collection $afterCreating = null,
		$connection = null,
		?Collection $recycle = null)
	{
		parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
		self::$goroskopId = 0;
	}

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'gor_id'			=> self::$goroskopId++,
			'gor_name'			=> $this->faker->name(10),
			'gor_type'			=> 1,
			'gor_dopoln'		=> 1,
			'gor_text'			=> $this->faker->text(100)
		];
	}
}