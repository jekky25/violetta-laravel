<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Services\EvaluationService;
use App\Models\User;

class EvaluationControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_store_vote_returns_success()
	{
		$authUser = User::factory()->create();
		$user = User::factory()->create();

		$this->actingAs($authUser);

		$service = \Mockery::mock(EvaluationService::class);
		$this->app->instance(EvaluationService::class, $service);

		$service->shouldReceive('vote')
			->once()
			->with(
				$user->id,
				$authUser,
				['vote' => 5]
			);


		$response = $this->postJson(route('evaulation.store', $user->id), [
			'vote' => 5
		]);

		$response->assertStatus(200)
			->assertJson(['success' => true]);
	}

	public function test_store_vote_fails_if_not_authenticated()
	{
		$this->withoutExceptionHandling();
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Пользователь не авторизован');

		$response = $this->postJson(route('evaulation.store', 20), [
			'vote' => 5
		]);

		$response->assertStatus(401);
	}

	public function test_store_vote_validation_fails()
	{
		$authUser = User::factory()->create();
		$user = User::factory()->create();
		$this->actingAs($authUser);

		$response = $this->postJson(route('evaulation.store', $user->id), [
			'vote' => 10
		]);

		$response->assertStatus(422);
	}
}