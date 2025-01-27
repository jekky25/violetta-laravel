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
		$city = City::get()->random();
		return [
			'user_name'					=> $this->faker->name(),
			'user_city'					=> !empty($city)	? $city->id			: 0,
			'user_country'				=> !empty($city)	? $city->country_id	: 0,
			'user_region'				=> !empty($city)	? $city->region_id	: 0,
			'user_active'				=> 1,
			'user_mail'					=> $this->faker->unique()->safeEmail(),
			'user_reiting'				=> $this->faker->numberBetween(1, 5),
			'user_login'				=> Str::random(10),
			'user_password'				=> $this->faker->unique()->password(6, 8),
			'user_hash'					=> md5($this->faker->unique()->password(6, 8)),
			'user_speak_lang'			=> '',
			'user_partner_body'			=> '',
			'user_partner_speak_lang'	=> '',
			'user_partner_education'	=> '',
			'user_partner_smoke'		=> '',
			'user_partner_spirt'		=> '',
			'user_description'			=> $this->faker->text(100),
			'user_target_meet'			=> '',
			'user_interests'			=> '',
			'user_partner_description'	=> $this->faker->text(100),
			'user_phone'				=> '123456789',
			'user_url'					=> '',
			'user_ip'					=> '127.0.0.1',
			'remember_token'			=> Str::random(10)
        ];
    }
}
