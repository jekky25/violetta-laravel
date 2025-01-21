<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Region;

class CityFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$region	= Region::get()->random();
		return [
			'country_id'				=> !empty($region) ? $region->country_id : 0,
			'regions_id'				=> !empty($region) ? $region->id : 0,
			'name'						=> $this->faker->name(),
        ];
    }
}
