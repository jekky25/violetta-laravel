<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Services\PhotoService;
use App\Services\FileService;
use App\Models\User;
use App\Models\Photo;
use App\Repositories\PhotoRepository;
use App\Interfaces\UserInterface;
use App\Interfaces\VarsInterface;
use App\Interfaces\AnketVisitInterface;
use App\DTO\CreatePhotoDTO;
use App\DTO\UpdatePhotoDTO;
use Tests\Traits\hasSetupPrepare;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PhotoServiceTest extends TestCase
{
    use hasSetupPrepare;

    protected $photoRepository;
    protected $fileService;
    protected $userRepository;
    protected $varsRepository;
    protected $service;

    protected function setUp(): void
    {
		parent::$migrated = true;
        parent::setUp();
		self::setUpPrepare();

        $this->photoRepository = Mockery::mock(PhotoRepository::class);
        $this->fileService = Mockery::mock(FileService::class);
        $this->userRepository = Mockery::mock(UserInterface::class);
        $this->varsRepository = Mockery::mock(VarsInterface::class);
        $this->anketVisitRepository = Mockery::mock(AnketVisitInterface::class);

        $this->varsRepository
            ->shouldReceive('getAll')
            ->andReturn([]);

        $this->service = new PhotoService(
            $this->varsRepository,
            $this->userRepository,
            $this->photoRepository,
            $this->anketVisitRepository,
            $this->fileService
        );
    }

    public function test_it_creates_photo()
    {
        $user = User::factory()->make(['id' => 10001]);

		$fileServiceReturn = 
		[
			"link" => "E:\OSPanel_6\temp\upload\php524A.tmp",
			"name" => "IMG_0079t.jpg",
			"size" => 1405482,
			"filetype" => "image/jpeg",
			"extension" => "jpg",
			"unic_name" => "JcsQ9MUUBKWPwXnQWyhQ.jpg"
		];

		$file = UploadedFile::fake()->image('photo.jpg');

		$dto = new CreatePhotoDTO($file);

		$this->fileService
			->shouldReceive('fotoUpload')
			->once()
			->andReturn($fileServiceReturn);

		$this->photoRepository
			->shouldReceive('create')
			->once()
			->andReturn(new Photo());

		$this->service->create($user, $dto);

		$this->assertTrue(true);
    }

    public function test_it_updates_photo()
    {
		$userId = 10002;
		$photoId = 1;
        $user = User::factory()->make(['id' => $userId]);

        $photo = new Photo([
            'id' => $photoId,
			'user_id' => $userId,
            'path' => 'old.jpg'
        ]);

		$file = UploadedFile::fake()->image('photo.jpg');

		$dto = new UpdatePhotoDTO($file);

        $this->photoRepository
            ->shouldReceive('getByIdAndUserId')
            ->andReturn($photo);

        $this->fileService
            ->shouldReceive('fotoUpload')
            ->once()
            ->andReturn(['unic_name' => 'new.jpg']);

        $this->fileService
            ->shouldReceive('fotoDelete')
            ->once();

        $this->photoRepository
            ->shouldReceive('update')
            ->once();

        $this->service->update($photoId, $user, $dto);

        $this->assertTrue(true);
    }

    public function test_it_deletes_photo()
    {
        $user = User::factory()->make(['id' => 1]);

        $photo = new Photo([
            'id' => 1,
            'main_picture' => 0
        ]);

        $this->photoRepository
            ->shouldReceive('getByIdAndUserId')
            ->andReturn($photo);

        $this->fileService
            ->shouldReceive('fotoDelete')
            ->once();

        $this->photoRepository
            ->shouldReceive('destroyPhoto')
            ->once();

        $this->service->destroy(1, $user);

        $this->assertTrue(true);
    }

    public function test_it_sets_main_picture()
    {
        $user = User::factory()->make(['id' => 1]);

        $photo = new Photo([
            'id' => 1,
            'main_picture' => 0
        ]);

        $this->photoRepository
            ->shouldReceive('getFirstByUserId')
            ->andReturn($photo);

        $this->photoRepository
            ->shouldReceive('getAllByUserId')
            ->andReturn(new Collection([$photo]));

        $result = $this->service->setMainPicture($user);

        $this->assertTrue($result);
    }

    public function test_it_returns_main_photo()
    {
        $photo = new \App\Models\Photo(['id' => 10]);

        $this->photoRepository->shouldReceive('getMainPhotoByUserId')
            ->once()
            ->with(5)
            ->andReturn($photo);

        $result = $this->service->getMainPhoto(5);

        $this->assertEquals($photo, $result);
    }

    public function test_it_returns_null_if_no_main_photo()
    {
        $this->photoRepository->shouldReceive('getMainPhotoByUserId')
            ->once()
            ->andReturn(null);

        $result = $this->service->getMainPhoto(5);
        $this->assertNull($result);
    }

    public function test_it_returns_photo_page_data()
    {
        $user = User::factory()->create();
        $photo = Photo::factory()->create(['user_id' => $user->id]);
        $authUser = User::factory()->create();

        $this->photoRepository->shouldReceive('getById')
            ->once()
            ->with($photo->id)
            ->andReturn($photo);

        $this->userRepository->shouldReceive('getById')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $this->anketVisitRepository->shouldReceive('update')
            ->once()
            ->with($user->id, config('profile.visit_days'), $authUser->id)
            ->andReturn($user->ankVisits);

        $result = $this->service->getPhotoPageData($photo->id, $authUser);

        $this->assertEquals($photo, $result['photo']);
        $this->assertEquals($user, $result['userData']);
    }

    public function test_it_aborts_if_photo_not_found()
    {
        $user = User::factory()->create();
        $this->photoRepository->shouldReceive('getById')
            ->once()
            ->andThrow(new ModelNotFoundException());

        $this->expectException(ModelNotFoundException::class);            
        $this->service->getPhotoPageData(1, $user);
    }
}