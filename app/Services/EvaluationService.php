<?php
namespace App\Services;

use App\Models\User;
use App\Interfaces\AnketEvaluationInterface;

class EvaluationService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(protected AnketEvaluationInterface $repository) {}

	public function vote(int $userId, ?User $authUser, array $data)
	{
		if ($authUser === null) throw new \Exception('Пользователь не авторизован');

		$this->repository->updateOrCreate(
			['user_id' => $authUser->id, 'user_id_ocenka' => $userId],
			['ball' => $data['vote'], 'time' => time()]);
	}
}