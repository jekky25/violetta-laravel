<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\ApiAuthService;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;
use App\Http\Resources\Profile\AuthProfileResource;
use Illuminate\Http\Response;
use Tests\Traits\hasSetupPrepare;

class ApiAuthServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $repository;
	protected ApiAuthService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(UserInterface::class);

		$this->service = new ApiAuthService(
			$this->repository
		);
	}

	public function test_login_api_returns_user_resource()
	{
		$user = User::factory()->make([
			'id' => 1
		]);

		$data = [
			'login' => 'test',
			'password' => '123456'
		];

		$this->repository->shouldReceive('getByLoginAndPass')
			->once()
			->with('test', '123456')
			->andReturn($user);

		Auth::shouldReceive('login')
			->once()
			->with($user, true);

		$result = $this->service->loginApi($data);

		$this->assertInstanceOf(
			AuthProfileResource::class,
			$result
		);
	}

	public function test_login_api_returns_400_if_user_not_found()
	{
		$data = [
			'login' => 'wrong',
			'password' => 'wrong'
		];

		$this->repository->shouldReceive('getByLoginAndPass')
			->once()
			->with('wrong', 'wrong')
			->andReturn(null);

		$result = $this->service->loginApi($data);

		$this->assertInstanceOf(Response::class, $result);

		$this->assertEquals(400, $result->status());
	}
}
