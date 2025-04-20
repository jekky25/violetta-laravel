<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\City;

class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$city	 = City::get()->random();
		return [
			'name'						=> $this->faker->name(),
			'sex'						=> rand(1, 2),
			'city_id'					=> !empty($city)	? $city->id			: 0,
			'country_id'				=> !empty($city)	? $city->country_id	: 0,
			'region_id'					=> !empty($city)	? $city->region_id	: 0,
			'active'					=> 1,
			'email'						=> $this->faker->unique()->safeEmail(),
			'rating'					=> $this->faker->numberBetween(1, 5),
			'login'						=> Str::random(10),
			'password'					=> $this->faker->unique()->password(6, 8),
			'hash'						=> md5($this->faker->unique()->password(6, 8)),
			'speak_lang'				=> '',
			'partner_body'				=> '',
			'partner_languages'			=> '',
			'partner_education'			=> '',
			'partner_smoke'				=> '',
			'partner_alcohol'			=> '',
			'description'				=> $this->faker->text(100),
			'targets'					=> '',
			'interests'					=> '',
			'partner_description'		=> $this->faker->text(100),
			'phone'						=> '123456789',
			'url'						=> '',
			'ip'						=> '127.0.0.1',
			'remember_token'			=> Str::random(10)
		];
	}
}
