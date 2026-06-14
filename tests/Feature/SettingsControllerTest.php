<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\SettingsService;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class SettingsControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $user;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->user = User::factory()->create();
		$this->actingAs($this->user);
	}

    public function test_index_settings_returns_view(): void
    {
		$_SERVER['REQUEST_URI'] = route('registration.edit.settings');
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200);
        $response->assertViewIs('registration.settings');
        $response->assertViewHas('fields');
    }

    public function test_settings_update_calls_service_and_redirects(): void
    {
        $service = Mockery::mock(SettingsService::class);
        $this->app->instance(SettingsService::class, $service);

        $data = [
            'dont_send_email' => 1
        ];

        $service->shouldReceive('update')
            ->once()
            ->with($this->user, $data);

		$_SERVER['REQUEST_URI'] = route('registration.edit.settings.post');
        $response = $this->put($_SERVER['REQUEST_URI'], $data);
		$_SERVER['REQUEST_URI'] = route('registration.edit.settings');
        $response->assertRedirect($_SERVER['REQUEST_URI']);
		$response->assertSessionHas('success');
    }
}