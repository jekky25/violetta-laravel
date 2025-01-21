<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;

class RegionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$countryId = Country::get()->random()->id;
		return [
			'country_id'				=> $countryId,
			'name'						=> $this->faker->name(),
        ];
    }
}
