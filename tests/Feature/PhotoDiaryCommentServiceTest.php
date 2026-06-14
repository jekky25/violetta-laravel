<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\DiaryComment;
use App\Services\FileService;
use App\Services\PhotoDiaryCommentService;
use App\Interfaces\DiaryCommentInterface;
use Tests\Traits\hasSetupPrepare;

class PhotoDiaryCommentServiceTest extends TestCase
{
    use hasSetupPrepare;

    protected $diaryCommentRepository;

    protected $fileService;

    protected PhotoDiaryCommentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        self::setUpPrepare();

        $this->diaryCommentRepository = Mockery::mock(
            DiaryCommentInterface::class
        );

        $this->fileService = Mockery::mock(
            FileService::class
        );

        $this->service = new PhotoDiaryCommentService(
            $this->diaryCommentRepository,
            $this->fileService
        );
    }

    public function test_destroy_removes_photo_and_updates_comment()
    {
        $user = User::factory()->make([
            'id' => 5
        ]);

        $comment = DiaryComment::factory()->make([
            'picture' => 'photo.jpg'
        ]);

        $this->diaryCommentRepository
            ->shouldReceive('getByUserAndId')
            ->once()
            ->with(10, 5)
            ->andReturn($comment);

        $this->fileService
            ->shouldReceive('remove')
            ->once()
            ->with($comment->picture_url);

        $this->diaryCommentRepository
            ->shouldReceive('update')
            ->once()
            ->with(10, [
                'picture' => 0
            ]);

        $this->service->destroy(10, $user);

        $this->assertTrue(true);
    }

    public function test_destroy_throws_exception()
    {
        $this->expectException(\Exception::class);

        $user = User::factory()->make([
            'id' => 5
        ]);

        $comment = DiaryComment::factory()->make([
            'picture' => 'photo.jpg'
        ]);

        $this->diaryCommentRepository
            ->shouldReceive('getByUserAndId')
            ->once()
            ->with(10, 5)
            ->andReturn($comment);

        $this->fileService
            ->shouldReceive('remove')
            ->once()
            ->andThrow(new \Exception('File delete error'));

        $this->service->destroy(10, $user);
    }
}