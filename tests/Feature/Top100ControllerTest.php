<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\Top100Service;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class Top100ControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_show_returns_view(): void
    {
		$_SERVER['REQUEST_URI'] = route('registration.top100');
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200);
        $response->assertViewIs('registration.top100');
    }

    public function test_update_calls_service_and_redirects(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $service = Mockery::mock(Top100Service::class);
        $this->app->instance(Top100Service::class, $service);

        $service->shouldReceive('update')
            ->once()
            ->with($user)
            ->andReturn(['success' => 'Информация сохранена.']);

		$_SERVER['REQUEST_URI'] = route('registration.top100.post');
        $response = $this->post($_SERVER['REQUEST_URI']);

        $response->assertRedirect($_SERVER['REQUEST_URI']);
        $response->assertSessionHas('success');
    }
}