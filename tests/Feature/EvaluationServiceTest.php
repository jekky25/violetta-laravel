<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Interfaces\UserInterface;
use App\Interfaces\AnketEvaluationInterface;
use App\Services\EvaluationService;
use App\Models\User;

class EvaluationServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_it_votes_successfully()
	{
		$userId = 20;

		$repository = \Mockery::mock(AnketEvaluationInterface::class);
		$userRepository = \Mockery::mock(UserInterface::class);
		
		$service = new EvaluationService($repository,$userRepository);

		$authUser = User::factory()->create();

		$data = ['vote' => 5];

		$userRepository->shouldReceive('getJustById')
			->once()
			->with($userId)
			->andReturn(true);

		$repository->shouldReceive('updateOrCreate')
			->once()
			->with(
				['user_id' => $authUser->id, 'user_id_ocenka' => $userId],
				\Mockery::on(function ($arg) {
					return $arg['ball'] === 5 && isset($arg['time']);
				})
			);

		$service->vote($userId, $authUser, $data);
	}

	public function test_it_throws_exception_if_user_not_authenticated()
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Пользователь не авторизован');

		$repository = \Mockery::mock(AnketEvaluationInterface::class);
		$userRepository = \Mockery::mock(UserInterface::class);
		$service = new \App\Services\EvaluationService($repository,$userRepository);

		$service->vote(20, null, ['vote' => 3]);
	}
}