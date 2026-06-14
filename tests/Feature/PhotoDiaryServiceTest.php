<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Diary;
use App\Services\FileService;
use App\Services\PhotoDiaryService;
use App\Interfaces\DiaryInterface;
use Tests\Traits\hasSetupPrepare;

class PhotoDiaryServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $diaryRepository;

	protected $fileService;

	protected PhotoDiaryService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->diaryRepository = Mockery::mock(
			DiaryInterface::class
		);

		$this->fileService = Mockery::mock(
			FileService::class
		);

		$this->service = new PhotoDiaryService(
			$this->diaryRepository,
			$this->fileService
		);
	}

	public function test_destroy_removes_photo_and_updates_diary()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->make([
			'picture' => 'photo.jpg'
		]);

		$this->diaryRepository
			->shouldReceive('getByUserAndId')
			->once()
			->with(10, 5)
			->andReturn($diary);

		$this->fileService
			->shouldReceive('remove')
			->once()
			->with($diary->picture_url);

		$this->diaryRepository
			->shouldReceive('update')
			->once()
			->with(10, [
				'picture' => 0
			]);

		$this->service->destroy(10, $user);

		$this->assertTrue(true);
	}

	public function test_destroy_throws_exception_when_file_remove_failed()
	{
		$this->expectException(\Exception::class);

		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->make([
			'picture' => 'photo.jpg'
		]);

		$this->diaryRepository
			->shouldReceive('getByUserAndId')
			->once()
			->with(10, 5)
			->andReturn($diary);

		$this->fileService
			->shouldReceive('remove')
			->once()
			->andThrow(
				new \Exception('File delete error')
			);

		$this->service->destroy(10, $user);
	}
}
