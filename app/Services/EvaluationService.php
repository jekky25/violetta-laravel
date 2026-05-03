<?php
namespace App\Services;

use App\Models\User;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\UserInterface;

class EvaluationService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(
		protected AnketEvaluationInterface $repository,
		protected UserInterface $userRepository
	) {}

	public function vote(int $userId, ?User $authUser, array $data)
	{
		if ($authUser === null) throw new \Exception('Пользователь не авторизован');
		if ($this->userRepository->getJustById($userId) === null)  throw new \Exception('Пользователь не найден');

		$this->repository->updateOrCreate(
			['user_id' => $authUser->id, 'user_id_ocenka' => $userId],
			['ball' => $data['vote'], 'time' => now()->timestamp]);
	}
}