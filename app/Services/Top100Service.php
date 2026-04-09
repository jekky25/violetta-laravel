<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Models\User;

class Top100Service
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		private UserInterface $repository
	) {}


	/**
	* Update the top100 time
	*/
	public function update(User $user): array
	{
		if ($user->photo->isEmpty()) return ['conditions' => true];
		$this->repository->update($user, ['top100' => time()]);
		return ['success' => 'Информация сохранена.'];
	}
}