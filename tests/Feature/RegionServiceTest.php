<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RegionService;
use App\Interfaces\RegionInterface;
use Illuminate\Support\Collection;
use Mockery;

class RegionServiceTest extends TestCase
{
	protected $repository;
	protected $service;

	protected function setUp(): void
	{
		parent::setUp();

		$this->repository = Mockery::mock(RegionInterface::class);
		$this->service = new RegionService($this->repository);
	}

	public function test_get_list_returns_collection()
	{
		$fakeData = collect([
			(object)['id' => 1, 'name' => 'Region 1']
		]);

		$this->repository
			->shouldReceive('getByCountryId')
			->once()
			->with(1)
			->andReturn($fakeData);

		$result = $this->service->getList(1);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertCount(1, $result);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}
