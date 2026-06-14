<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CommentScreenService;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\Models\User;
use App\DTO\CreateCommentScreenDTO;
use App\Services\ScreenService;
use App\Models\Screen;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class CommentScreenServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(ScreenInterface::class);
		$this->commentRepository = Mockery::mock(CommentScreenInterface::class);

		$this->service = new ScreenService(
			$this->repository,
			$this->commentRepository
		);
	}

	public function test_store_creates_comment()
	{
		$screen = Screen::factory()->create();
		$user = User::factory()->create();

		$this->repository->shouldReceive('getById')
			->once()
			->with($screen->id)
			->andReturn($screen);

		$dto = CreateCommentScreenDTO::fromRequest(
			$screen->id,
			$user,
			['description' => 'test']
		);

		$this->commentRepository->shouldReceive('create')
			->once()
			->with(Mockery::on(function ($data) use ($dto, $user) {
				return $data['scr_id'] === $dto->screenId
					&& $data['user_id'] === $user->id
					&& $data['description'] === $dto->description;
			}));

		$service = new CommentScreenService($this->repository, $this->commentRepository);

		$service->store($dto);

		$this->assertTrue(true);
	}
}
