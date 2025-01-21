<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PhotoFactory extends Factory
{
	private static $userPhoto = [];

	public static function resetPhoto()
	{
		self::$userPhoto = [];
	}

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		$userId = User::get()->random()->user_id;
		self::$userPhoto[$userId] = isset(self::$userPhoto[$userId]) ? (self::$userPhoto[$userId] + 1) : 1;
		$isPortret = self::$userPhoto[$userId] > 1 ? 0 : 1;
		return [
			'user_id'					=> $userId,
			'fotos_portret'				=> $isPortret,
			'fotos_t'					=> time()
        ];
    }
}
