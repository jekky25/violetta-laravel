<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Diary;
use App\Models\DiaryComment;
use App\Services\FileService;
use App\Services\DiaryCommentService;
use App\Interfaces\DiaryInterface;
use App\DTO\DiaryCommentDTO;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Http\UploadedFile;
use App\Interfaces\DiaryCommentInterface;

class DiaryCommentServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $diaryCommentRepository;
	protected $diaryRepository;
	protected $fileService;

	protected DiaryCommentService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->diaryCommentRepository = Mockery::mock(
			DiaryCommentInterface::class
		);

		$this->diaryRepository = Mockery::mock(
			DiaryInterface::class
		);

		$this->fileService = Mockery::mock(
			FileService::class
		);

		$this->service = new DiaryCommentService(
			$this->diaryCommentRepository,
			$this->diaryRepository,
			$this->fileService
		);
	}

	public function test_get_index_data()
	{
		$diary = Diary::factory()->make();

		$comments = collect([
			DiaryComment::factory()->make()
		]);

		$this->diaryRepository->shouldReceive('getById')
			->once()
			->with(1)
			->andReturn($diary);

		$this->diaryCommentRepository
			->shouldReceive('getByDiary')
			->once()
			->with(10, 1)
			->andReturn($comments);

		$result = $this->service->getIndexData(1, 10);

		$this->assertEquals($diary, $result['diary']);

		$this->assertEquals(
			$comments,
			$result['comments']
		);
	}

	public function test_edit_returns_comment()
	{
		$comment = DiaryComment::factory()->make();

		auth()->login(User::factory()->create());

		$this->diaryCommentRepository
			->shouldReceive('getByUserAndId')
			->once()
			->andReturn($comment);

		$result = $this->service->edit(1);

		$this->assertEquals($comment, $result);
	}

	public function test_create_comment_without_photo()
	{
		$dto = new DiaryCommentDTO(
			diaryId: 1,
			userId: 2,
			title: 'Title',
			description: 'Text',
			photo: null
		);

		$this->diaryCommentRepository
			->shouldReceive('create')
			->once();

		$this->service->create($dto);

		$this->assertTrue(true);
	}

	public function test_create_comment_with_photo()
	{
		$photo = UploadedFile::fake()->image('test.jpg');

		$dto = new DiaryCommentDTO(
			diaryId: 1,
			userId: 2,
			title: 'Title',
			description: 'Text',
			photo: $photo
		);

		$this->fileService->shouldReceive('fotoUpload')
			->once()
			->andReturn([
				'unic_name' => 'photo.jpg'
			]);

		$this->diaryCommentRepository
			->shouldReceive('create')
			->once();

		$this->service->create($dto);

		$this->assertTrue(true);
	}

	public function test_update_comment()
	{
		$user = User::factory()->make([
			'id' => 1
		]);

		$comment = DiaryComment::factory()->make();

		$dto = new DiaryCommentDTO(
			diaryId: 1,
			userId: 1,
			title: 'Updated',
			description: 'Updated text',
			photo: null
		);

		$this->diaryCommentRepository
			->shouldReceive('getByUserAndId')
			->once()
			->with(5, 1)
			->andReturn($comment);

		$this->diaryCommentRepository
			->shouldReceive('update')
			->once();

		$result = $this->service->update(
			5,
			$user,
			$dto
		);

		$this->assertEquals($comment, $result);
	}

	public function test_destroy_comment()
	{
		$user = User::factory()->make([
			'id' => 1
		]);

		$comment = DiaryComment::factory()->create([
			'picture' => 'test.jpg'
		]);

		$this->diaryCommentRepository
			->shouldReceive('getByUserAndId')
			->once()
			->with($comment->id, $user->id)
			->andReturn($comment);

		$this->fileService
			->shouldReceive('remove')
			->once()
			->with('img/dnev_comment/test.jpg');

		$this->diaryCommentRepository
			->shouldReceive('delete')
			->once()
			->with($comment);

		$result = $this->service->destroy(
			$comment->id,
			$user
		);

		$this->assertEquals($comment, $result);
	}
}
