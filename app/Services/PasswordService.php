<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private UserRepository $repository
		) {}

	/**
	* Update user
	*/
	public function update(User $user, array $data): void
	{
		$_data = [
			'password' => $data['pass'],
			'hash' => Hash::make($data['pass'])
		];
		$this->repository->update($user, $_data);
	}
}