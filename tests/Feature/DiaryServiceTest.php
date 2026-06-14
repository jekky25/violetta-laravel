<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Diary;
use App\DTO\DiaryDTO;
use App\Services\FileService;
use App\Services\DiaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\DiaryInterface;
use App\Interfaces\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Traits\hasSetupPrepare;

class DiaryServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $repository;

	protected $userRepository;

	protected $fileService;

	protected DiaryService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(
			DiaryInterface::class
		);

		$this->userRepository = Mockery::mock(
			UserInterface::class
		);

		$this->fileService = Mockery::mock(
			FileService::class
		);

		$this->service = new DiaryService(
			$this->repository,
			$this->userRepository,
			$this->fileService
		);
	}

	public function test_index_returns_diaries()
	{
		$paginator = new LengthAwarePaginator([], 0, 10);

		$this->repository->shouldReceive('getAll')
			->once()
			->with(10)
			->andReturn($paginator);

		$result = $this->service->index(10);

		$this->assertEquals($paginator, $result);
	}

	public function test_my_diaries_returns_user_diaries()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$paginator = new LengthAwarePaginator([], 0, 10);

		$this->repository->shouldReceive('getByUser')
			->once()
			->with(10, 5)
			->andReturn($paginator);

		$result = $this->service->myDiaries(10, $user);

		$this->assertEquals($paginator, $result);
	}

	public function test_create_creates_diary()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$photo = Mockery::mock(UploadedFile::class);

		$dto = new DiaryDTO(
			title: 'Title',
			description: 'Description',
			photo: $photo
		);

		$this->fileService->shouldReceive('fotoUpload')
			->once()
			->andReturn([
				'unic_name' => 'photo.jpg'
			]);

		$this->repository->shouldReceive('create')
			->once();

		$this->service->create($dto, $user);

		$this->assertTrue(true);
	}

	public function test_get_show_data_returns_data()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->create([
			'user_id' => $user->id
		]);

		$perPage = 10;

		$paginator = new LengthAwarePaginator([$diary], 1, $perPage);

		$this->userRepository->shouldReceive('getById')
			->once()
			->with($user->id)
			->andReturn($user);

		$this->repository->shouldReceive('getByUser')
			->once()
			->with($perPage, $user->id)
			->andReturn($paginator);

		$result = $this->service->getShowData($perPage, $user->id);

		$this->assertEquals($user, $result['userData']);

		$this->assertEquals($paginator, $result['diaries']);
	}

	public function test_get_show_data_aborts_if_empty()
	{
		$this->expectException(
			NotFoundHttpException::class
		);

		$paginator = new LengthAwarePaginator([], 0, 10);

		$this->userRepository->shouldReceive('getById')
			->once()
			->andReturn(null);

		$this->repository->shouldReceive('getByUser')
			->once()
			->andReturn($paginator);

		$this->service->getShowData(10, 1);
	}

	public function test_edit_returns_diary()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->make();

		$this->repository->shouldReceive('getByUserAndId')
			->once()
			->with(1, 5)
			->andReturn($diary);

		$result = $this->service->edit(1, $user);

		$this->assertEquals($diary, $result);
	}

	public function test_update_updates_diary()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->make([
			'user_id' => 5
		]);

		$dto = new DiaryDTO(
			title: 'Updated',
			description: 'Updated desc',
			photo: null
		);

		$this->repository->shouldReceive('getByUserAndId')
			->once()
			->with(1, 5)
			->andReturn($diary);

		$this->repository->shouldReceive('update')
			->once()
			->with(1, Mockery::type('array'));

		$result = $this->service->update(
			1,
			$user,
			$dto
		);

		$this->assertEquals($diary, $result);
	}

	public function test_destroy_deletes_diary()
	{
		$user = User::factory()->make([
			'id' => 5
		]);

		$diary = Diary::factory()->make([
			'picture' => 'photo.jpg'
		]);

		$this->repository->shouldReceive('getByUserAndId')
			->once()
			->with(1, 5)
			->andReturn($diary);

		$this->fileService->shouldReceive('remove')
			->once()
			->with($diary->picture_url);

		$this->repository->shouldReceive('delete')
			->once()
			->with($diary);

		$result = $this->service->destroy(1, $user);

		$this->assertEquals($diary, $result);
	}
}
