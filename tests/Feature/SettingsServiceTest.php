<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SettingsService;
use App\Interfaces\UserInterface;
use Tests\Traits\hasSetupPrepare;
use App\Models\User;
use Mockery;

class SettingsServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_update_calls_repository(): void
	{
		$repository = Mockery::mock(UserInterface::class);

		$user = User::factory()->make();

		$data = [
			'email' => 'test@example.com',
			'name' => 'John',
		];

		$repository->shouldReceive('update')
			->once()
			->with($user, $data);

		$service = new SettingsService($repository);

		$service->update($user, $data);

		$this->assertTrue(true);
	}
}
