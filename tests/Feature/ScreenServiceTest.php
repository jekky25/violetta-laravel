<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ScreenService;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\DTO\ScreenSaverPageDTO;
use App\Models\Screen;
use Mockery;

class ScreenServiceTest extends TestCase
{
	protected $repository;
	protected $commentRepository;
	protected $service;

	protected function setUp(): void
	{
		parent::setUp();

		$this->repository = Mockery::mock(ScreenInterface::class);
		$this->commentRepository = Mockery::mock(CommentScreenInterface::class);

		$this->service = new ScreenService(
			$this->repository,
			$this->commentRepository
		);
	}

	public function test_get_list_returns_screens()
	{
		$perPage = config('pagination.screens');
        $this->repository->shouldReceive('get')
            ->once()
            ->with($perPage)
            ->andReturn(['screen1', 'screen2']);

        $service = new ScreenService($this->repository, $this->commentRepository);
        $result = $service->getList($perPage);
        $this->assertEquals(['screen1', 'screen2'], $result);
	}

	public function test_show_page_returns_dto()
    {
		$screenSaver = Screen::factory()->create([
			'date' => '2007-08-11',
			'name' => 'Водопад в горах',
			'path_scr' => '000001.scr',
			'path_rar' => '000001.rar',
			'path_jpg' => '000001.jpg',
			'screen_sub' => 1,
			'size_scr' => 1176770,
			'size_rar' => 813391,
			'zakachka' => 367
		]);

		$id = $screenSaver->id;

		$fakeData = collect([
			(object)[
			'id' => 1,
			'scr_id' => $id,
			'user_id' => 1,
			'create_time' => 2026,
			'name' => 'Администратор',
			'email' => 'jekky25@list.ru',
			'description' => 'bbbbbbbbbbbbbbb']]);

        $this->repository->shouldReceive('getById')->andReturn($screenSaver);
        $this->commentRepository->shouldReceive('getByScrId')->andReturn($fakeData);

        $service = new ScreenService($this->repository, $this->commentRepository);

		new ScreenSaverPageDTO(
			screen:	$this->repository->getById($id),
			comments: $this->commentRepository->getByScrId($id)
		);

        $dto = $service->showPage($id);

		$this->assertEquals($screenSaver, $dto->screen);
    }

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}