<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ConfirmService;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ConfirmControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_update_returns_view_with_confirmed_true(): void
    {
        $service = Mockery::mock(ConfirmService::class);
        $this->app->instance(ConfirmService::class, $service);

        $service->shouldReceive('confirmEmail')
            ->once()
            ->with(1, 'code')
            ->andReturn(true);

        $_SERVER['REQUEST_URI'] = route('registration.confirm', [1, 'code']);
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200);
        $response->assertViewIs('registration.confirm');
        $response->assertViewHas('confirmed', true);
    }

    public function test_update_returns_view_with_confirmed_false(): void
    {
        $service = Mockery::mock(ConfirmService::class);
        $this->app->instance(ConfirmService::class, $service);

        $service->shouldReceive('confirmEmail')
            ->once()
            ->andReturn(false);

        $_SERVER['REQUEST_URI'] = route('registration.confirm', [1, 'wrong-code']);
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200);
        $response->assertViewHas('confirmed', false);
    }
}