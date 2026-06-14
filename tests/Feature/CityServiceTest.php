<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CityService;
use App\Interfaces\CityInterface;
use Illuminate\Support\Collection;
use Mockery;

class CityServiceTest extends TestCase
{
	protected $repository;
	protected $service;

	protected function setUp(): void
	{
		parent::setUp();

		$this->repository = Mockery::mock(CityInterface::class);
		$this->service = new CityService($this->repository);
	}

	public function test_get_list_returns_collection()
	{
		$fakeData = collect([
			(object)['id' => 1, 'name' => 'City 1']
		]);

		$this->repository
			->shouldReceive('getByRegionId')
			->once()
			->with(10)
			->andReturn($fakeData);

		$result = $this->service->getList(10);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertCount(1, $result);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}
