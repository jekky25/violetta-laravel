<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ScreenService;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ScreenControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_index_returns_view()
	{
		$_SERVER['REQUEST_URI'] = route('screensavers');
		$service = Mockery::mock(ScreenService::class);
		$service->shouldReceive('getList')->andReturn([]);

		$this->app->instance(ScreenService::class, $service);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
		$response->assertViewIs('screensavers');
	}
}
