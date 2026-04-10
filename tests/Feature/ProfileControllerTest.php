<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\ProfileService;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ProfileControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

    public function test_update_main_calls_service_and_redirects(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = Mockery::mock(ProfileService::class);
        $this->app->instance(ProfileService::class, $service);

        $service->shouldReceive('updateMain')
            ->once()
            ->withArgs(function ($u, $dto) use ($user) {
                return $u->id === $user->id
                    && method_exists($dto, 'toArray');
            });

        $_SERVER['REQUEST_URI'] = route('registration.edit.post');
        $response = $this->put($_SERVER['REQUEST_URI'], [
            'name' => 'John',
            'birth_day' => 07,
            'birth_month' => 07,
            'birth_year' => 1991,
            "sex" => 2,
            "city_id" => 2952,
            "country_id" => 140,
            "region_id" => 596
        ]);

        $response->assertRedirect($_SERVER['REQUEST_URI']);
        $response->assertSessionHas('success');
    }
}