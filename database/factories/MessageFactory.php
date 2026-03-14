<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class MessageFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$userId = User::get()->random()->id;
		$userReceivedId = User::get()->random()->id;

		return [
			'sent_user_id'			=> $userId,
			'received_user_id'		=> $userReceivedId,
			'sent_is_deleted'		=> 0,
			'received_is_deleted'	=> 0,
			'create_time'			=> time(),
			'is_new'				=> 1,
			'description'			=> $this->faker->text(100)
		];
	}
}
