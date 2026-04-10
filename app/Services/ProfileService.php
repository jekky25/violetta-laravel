<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\DTO\UpdateMainProfileDTO;
use App\DTO\UpdateSecondProfileDTO;
use App\DTO\UpdatePartnerProfileDTO;
use App\Models\User;

class ProfileService
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
	 * Update main profile data 
	 */
	public function updateMain(User $user, UpdateMainProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}

	/**
	 * Update full profile data 
	 */
	public function updateSecond(User $user, UpdateSecondProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}

	/**
	 * Update partner profile data 
	 */
	public function updatePartner(User $user, UpdatePartnerProfileDTO $dto): void
	{
		$this->update($user, $dto->toArray());
	}	

	/**
	 * Update a profile using data 
	 */
	private function update(User $user, array $data)
	{
		$this->repository->update($user, $data);
	}
}