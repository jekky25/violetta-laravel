<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\NameService;
use App\Interfaces\NameInterface;
use Mockery;

class NameServiceTest extends TestCase
{
	protected $repository;
	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		$this->repository = Mockery::mock(NameInterface::class);
		$this->service = new NameService($this->repository);
	}

	public function test_get_name_page_data()
	{
		$fakeName = (object) [
			'id' => 1,
			'name' => 'Иван',
			'description' => "Описание\nтекста",
			'gender' => 'm'
		];

		$this->repository
			->shouldReceive('getById')
			->once()
			->with(1)
			->andReturn($fakeName);

		$result = $this->service->getNamePageData(1);

		$this->assertEquals('Иван', $result->name->name);
		$this->assertEquals('Значение имени Иван', $result->title);
		$this->assertStringContainsString('<br', $result->text);
		$this->assertEquals('men', $result->sex);
	}

	public function test_get_gender_page_data()
	{
		$fakeCollection = collect([
			(object)['name' => 'Анна']
		]);

		$this->repository
			->shouldReceive('getAllBySex')
			->once()
			->with('f', 1)
			->andReturn($fakeCollection);

		$result = $this->service->getGenderPageData('women', 1);

		$this->assertEquals('women', $result->sex);
		$this->assertEquals('Значение женского имени', $result->title);
		$this->assertCount(1, $result->names);
	}

	public function test_get_grouped_names()
	{
		config(['names.alphabet' => [1 => 'A', 2 => 'B']]);

		$this->repository
			->shouldReceive('getPartsByIds')
			->twice()
			->andReturn(collect([
				1 => collect([(object)['name' => 'Анна']])
			]));

		$result = $this->service->getGroupedNames();
		$this->assertArrayHasKey(1, $result['m']);
		$this->assertArrayHasKey('m', $result);
		$this->assertArrayHasKey('f', $result);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}