<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Services\ScreenDownloadService;
use Tests\Traits\hasSetupPrepare;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Mockery;

class ScreenDownloadControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
		config(['services.recaptcha.enabled' => false]);
	}

	public function test_download_calls_service()
	{
		$_SERVER['REQUEST_URI'] = route('screensavers.id.download', 1);

		$service = Mockery::mock(ScreenDownloadService::class);

		$service->shouldReceive('download')
			->once()
			->andReturn(new BinaryFileResponse(__FILE__));

		$this->app->instance(ScreenDownloadService::class, $service);

		$response = $this->post($_SERVER['REQUEST_URI'], [
			'f_download' => 1,
			'recaptcha_response' => 'test'
		]);
		$response->assertStatus(200);
	}
}