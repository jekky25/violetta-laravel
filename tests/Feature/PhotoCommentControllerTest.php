<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\PhotoCommentService;
use Tests\Traits\hasSetupPrepare;

class PhotoCommentControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(
			PhotoCommentService::class
		);

		$this->app->instance(
			PhotoCommentService::class,
			$this->service
		);
	}

	public function test_store_creates_comment_of_the_photo()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$this->service
			->shouldReceive('create')
			->once();

		$_SERVER['REQUEST_URI'] = route('ank.photo.id.post.comment', 15);
		$response = $this->post(
			$_SERVER['REQUEST_URI'],
			[
				'description' => 'Test comment'
			]
		);

		$response->assertRedirect();

		$response->assertSessionHas(
			'success',
			'Сообщение успешно отправлено'
		);
	}
}
