<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\PhotoDiaryService;
use Tests\Traits\hasSetupPrepare;

class PhotoDiaryControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(
			PhotoDiaryService::class
		);

		$this->app->instance(
			PhotoDiaryService::class,
			$this->service
		);
	}

	public function test_destroy_deletes_diary_photo()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$this->service
			->shouldReceive('destroy')
			->once()
			->with(15, $user);

		$_SERVER['REQUEST_URI'] = route('ank.diary.delete.photo.id', 15);
		$response = $this->delete($_SERVER['REQUEST_URI']);

		$response->assertRedirect(
			route('ank.diary.edit.id', 15)
		);
	}
}
