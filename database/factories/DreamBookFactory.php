<?php

namespace Database\Factories;

use Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class DreamBookFactory extends Factory
{
	private static $dreamBookId = 0;

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
		self::$dreamBookId = 0;
	}

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'id'				=> self::$dreamBookId++,
			'sonnik_id'			=> 1,
			'name'				=> $this->faker->name(10),
			'description'		=> $this->faker->text(100),
			'first_bukva'		=> 'T'
		];
	}
}