<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\WebAuthService;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;
use Illuminate\Http\RedirectResponse;
use Tests\Traits\hasSetupPrepare;

class WebAuthServiceTest extends TestCase
{
	use hasSetupPrepare;

    protected $repository;
    protected WebAuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
		self::setUpPrepare();

        $this->repository = Mockery::mock(UserInterface::class);

        $this->service = new WebAuthService(
            $this->repository
        );
    }

    public function test_login_api_logs_user_and_redirects_home()
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

        $response = $this->service->loginApi($data);

        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );

        $this->assertEquals(
            route('home'),
            $response->getTargetUrl()
        );
    }

    public function test_login_api_redirects_with_errors_if_user_not_found()
    {
        $data = [
            'login' => 'wrong',
            'password' => 'wrong'
        ];

        $this->repository->shouldReceive('getByLoginAndPass')
            ->once()
            ->with('wrong', 'wrong')
            ->andReturn(null);

        $response = $this->service->loginApi($data);

        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );

        $this->assertEquals(
            route('home'),
            $response->getTargetUrl()
        );

        $this->assertEquals(
            'Неверно указаны имя пользователя или пароль!',
            session('message')
        );

        $this->assertTrue(session('error'));
    }
}