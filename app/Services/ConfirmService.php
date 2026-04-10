<?php
namespace App\Services;

use App\Interfaces\UserInterface;

class ConfirmService
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
	 * Confirm Email after registration
	 */
	public function confirmEmail(int $userId, string $code): bool
	{
		if (empty($code)) abort(404);
		$user = $this->repository->getByIdAndConfirmCode($userId, $code);
		if ($user === null ) return false;

		$data = [
			'confirm_email'	=> 1,
			'submit_code'	=> ''
		];

		$this->repository->update($user, $data);
		return true;
	}
}