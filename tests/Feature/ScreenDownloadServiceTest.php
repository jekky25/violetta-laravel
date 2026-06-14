<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ScreenDownloadService;
use App\Interfaces\ScreenInterface;
use App\DTO\DownloadScreenDTO;
use App\Enums\ScreenSaverType;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class ScreenDownloadServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_download_returns_response()
	{
		$screenRepo = Mockery::mock(ScreenInterface::class);

		$screen = (object)[
			'id' => 1,
			'name' => '000001',
			'path_scr' => '000001.scr',
			'path_rar' => '000001.rar',
			'zakachka' => 0
		];

		$screenRepo->shouldReceive('getById')
			->once()
			->with(1)
			->andReturn($screen);

		$screenRepo->shouldReceive('update')
			->once()
			->with(Mockery::on(function ($updatedScreen) {
				return $updatedScreen->zakachka === 1;
			}));

		$service = new ScreenDownloadService($screenRepo);

		$dto = new DownloadScreenDTO(1, ScreenSaverType::VAR_SCR);
		$response = $service->download($dto);
		$this->assertEquals(200, $response->getStatusCode());
	}
}
