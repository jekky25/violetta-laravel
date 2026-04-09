<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Models\User;

class SettingsService
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
	 * Update settings
	 */
	public function update(User $user, array $data): void
	{
		$this->repository->update($user, $data);
	}
}