<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\PhotoCommentService;
use App\Interfaces\CommentPhotoInterface;
use App\DTO\PhotoCommentDTO;
use Tests\Traits\hasSetupPrepare;

class PhotoCommentServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $repository;

	protected PhotoCommentService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(
			CommentPhotoInterface::class
		);

		$this->service = new PhotoCommentService(
			$this->repository
		);
	}

	public function test_create_stores_comment()
	{
		$user = User::factory()->make([
			'id' => 25
		]);

		$dto = new PhotoCommentDTO(
			description: 'Test comment'
		);

		$this->repository
			->shouldReceive('store')
			->once()
			->with(Mockery::on(
				function ($data) use ($user) {

					return $data['foto_id'] === 10
						&& $data['user_id'] === $user->id
						&& $data['description'] === 'Test comment'
						&& isset($data['time']);
				}
			));

		$this->service->create(
			10,
			$user,
			$dto
		);

		$this->assertTrue(true);
	}
}
