<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\HoroscopeService;
use App\Factories\HoroscopeFactory;
use App\Interfaces\HoroscopeInterface;
use App\Interfaces\HoroscopeTypeInterface;
use App\DTO\HoroscopeIndexPageDTO;
use Mockery;

class HoroscopeServiceTest extends TestCase
{
	protected $repository;
	protected $typeRepository;
	protected $factory;
	protected $service;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();

		$this->repository = Mockery::mock(HoroscopeInterface::class);
		$this->typeRepository = Mockery::mock(HoroscopeTypeInterface::class);
		$this->factory = Mockery::mock(HoroscopeFactory::class);

		$this->service = new HoroscopeService(
			$this->repository,
			$this->typeRepository,
			$this->factory
		);
	}

	public function test_get_index_data_returns_dto()
	{
		$type = 1;

		$fakeHoroscope = Mockery::mock();
		$fakeHoroscope->shouldReceive('getText')->andReturn('text');
		$fakeHoroscope->shouldReceive('getTitle')->andReturn('title');

		$this->factory
			->shouldReceive('make')
			->with($type)
			->andReturn($fakeHoroscope);

		$this->repository
			->shouldReceive('getByType')
			->with($type)
			->andReturn(collect());

		$this->typeRepository
			->shouldReceive('getNotByType')
			->with($type)
			->andReturn(collect());

		$result = $this->service->getIndexData();

		$this->assertInstanceOf(HoroscopeIndexPageDTO::class, $result);
		$this->assertEquals('text', $result->zodiak_text);
		$this->assertEquals('title', $result->horoscope_title);
	}

	public function test_get_item_data_returns_dto()
	{
		$fakeEntity = (object)[
			'id' => 1,
			'type' => 2,
			'name' => 'Leo',
			'description' => 'Desc'
		];

		$this->repository
			->shouldReceive('getById')
			->with(1)
			->andReturn($fakeEntity);

		$this->repository
			->shouldReceive('getByType')
			->with(2)
			->andReturn(collect());

		$this->typeRepository
			->shouldReceive('getNotByType')
			->with(2)
			->andReturn(collect());

		$result = $this->service->getItemData(1);

		$this->assertInstanceOf(HoroscopeIndexPageDTO::class, $result);
		$this->assertEquals('Leo', $result->horoscope_title);
		$this->assertEquals('Desc', $result->zodiak_text);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}
