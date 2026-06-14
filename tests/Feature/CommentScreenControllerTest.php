<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CommentScreenService;
use Tests\Traits\hasSetupPrepare;
use App\Models\User;
use Mockery;

class CommentScreenControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_store_creates_comment()
	{
		$_SERVER['REQUEST_URI'] = route('screensavers.id', 1);
		$service = Mockery::mock(CommentScreenService::class);
		$service->shouldReceive('store')->once();

		$this->app->instance(CommentScreenService::class, $service);

		$user = User::factory()->create();

		$response = $this->actingAs($user)->post($_SERVER['REQUEST_URI'], [
			'description' => 'test'
		]);

		$response->assertRedirect();
	}
}