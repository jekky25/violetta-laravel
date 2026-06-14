<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ForgetPasswordService;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ForgetPasswordControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_send_password_calls_service_and_redirects(): void
    {
        $service = Mockery::mock(ForgetPasswordService::class);
        $this->app->instance(ForgetPasswordService::class, $service);

        $service->shouldReceive('sendPassword')
            ->once()
            ->with(Mockery::on(function ($dto) {
                return method_exists($dto, 'toArray');
            }));


        $_SERVER['REQUEST_URI'] = route('forget_pass.post', ['email' => 'test@mail.com']);
        $response = $this->post($_SERVER['REQUEST_URI']);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}