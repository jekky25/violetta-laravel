<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\ApiAuthService;
use Tests\Traits\hasSetupPrepare;
use \App\Http\Resources\Profile\AuthProfileResource;

class ApiAuthControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(ApiAuthService::class);

		$this->app->instance(ApiAuthService::class, $this->service);
	}

	public function test_me_returns_null_if_user_not_authenticated()
	{
		$_SERVER['REQUEST_URI'] = route('auth.get');
		$response = $this->getJson($_SERVER['REQUEST_URI']);

		$response->assertOk();
		$this->assertEquals('', $response->getContent());
	}

	public function test_me_returns_authenticated_user()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$_SERVER['REQUEST_URI'] = route('auth.get');
		$response = $this->getJson($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertJsonFragment([
			'id' => $user->id,
		]);
	}

	public function test_login_returns_user_resource()
	{
		$user = User::factory()->create();

		$data = [
			'login' => 'test',
			'password' => '123456'
		];

		$resource = new AuthProfileResource($user);

		$this->service->shouldReceive('loginApi')
			->once()
			->with($data)
			->andReturn($resource);

		$_SERVER['REQUEST_URI'] = route('login.api');

		$response = $this->postJson($_SERVER['REQUEST_URI'], $data);
		$response->assertStatus(201);
	}
}
