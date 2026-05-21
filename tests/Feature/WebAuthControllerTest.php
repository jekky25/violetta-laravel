<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\WebAuthService;
use Tests\Traits\hasSetupPrepare;

class WebAuthControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(WebAuthService::class);
		$this->app->instance(WebAuthService::class, $this->service);
	}

	public function test_logout_redirects_home()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$_SERVER['REQUEST_URI'] = route('logout');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertRedirect(route('home'));
		$this->assertGuest();
	}

	public function test_login_redirects_home_on_success()
	{
		$data = [
			'login' => 'test',
			'password' => '123456'
		];

		$this->service->shouldReceive('loginApi')
			->once()
			->with($data)
			->andReturn(redirect()->route('home'));

		$_SERVER['REQUEST_URI'] = route('login');
		$response = $this->post($_SERVER['REQUEST_URI'], $data);

		$response->assertStatus(302);
	}
}
