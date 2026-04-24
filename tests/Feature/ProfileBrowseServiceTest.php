<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use Tests\Traits\hasSetupPrepare;

class ProfileBrowseServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_get_list_without_filters_returns_new_faces()
	{
		$repo = \Mockery::mock(\App\Interfaces\UserInterface::class);

		$service = new \App\Services\ProfileBrowseService($repo);

		$repo->shouldReceive('newFaces')
			->once()
			->andReturn(collect());

		$result = $service->getList(
			\Mockery::mock(\App\Requests\AnketsRequest::class),
			\Mockery::mock(\App\Filters\AnketsFilter::class),
			null,
			null
		);

		$this->assertArrayHasKey('ankets', $result);
	}

	public function test_get_list_with_filters_calls_search()
	{
		$repo = \Mockery::mock(\App\Interfaces\UserInterface::class);

		$service = new \App\Services\ProfileBrowseService($repo);

		$repo->shouldReceive('getBySearch')
			->once()
			->andReturn(collect());

		$result = $service->getList(
			\Mockery::mock(\App\Requests\AnketsRequest::class),
			\Mockery::mock(\App\Filters\AnketsFilter::class),
			'men',
			'2535'
		);

		$this->assertArrayHasKey('ankets', $result);
	}

	public function test_get_popular()
	{
		$repo = \Mockery::mock(\App\Interfaces\UserInterface::class);

		$service = new \App\Services\ProfileBrowseService($repo);

		$repo->shouldReceive('getBySearch')
			->once()
			->andReturn(collect());

		$result = $service->getPopular(
			\Mockery::mock(\App\Requests\UserPopularRequest::class),
			\Mockery::mock(\App\Filters\UserPopularFilter::class),
			'men'
		);

		$this->assertEquals('мужчины', $result['popSex']);
	}

	public function test_get_best()
	{
		$repo = \Mockery::mock(\App\Interfaces\UserInterface::class);

		$service = new \App\Services\ProfileBrowseService($repo);

		$repo->shouldReceive('getBySearch')
			->once()
			->andReturn(collect());

		$user = \App\Models\User::factory()->make();

		$result = $service->getBest(
			\Mockery::mock(\App\Requests\UserBestRequest::class),
			\Mockery::mock(\App\Filters\UserBestFilter::class),
			'men',
			$user
		);

		$this->assertEquals('Лучшие парни', $result['titleId']);
	}

	public function test_get_top100()
	{
		$repo = \Mockery::mock(\App\Interfaces\UserInterface::class);

		$service = new \App\Services\ProfileBrowseService($repo);

		$user = \App\Models\User::factory()->make();

		$repo->shouldReceive('getTop100')
			->once()
			->andReturn($user);

		$result = $service->getTop100(1, 10);

		$this->assertInstanceOf(\App\Models\User::class, $result);
	}
}
