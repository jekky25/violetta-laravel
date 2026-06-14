<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\ForumService;
use App\Interfaces\ForumInterface;
use Tests\Traits\hasSetupPrepare;

class ForumServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected $repository;

	protected ForumService $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->repository = Mockery::mock(
			ForumInterface::class
		);

		$this->service = new ForumService(
			$this->repository
		);
	}

	public function test_get_top_returns_topics()
	{
		$topics = [
			[
				'id' => 1,
				'title' => 'Topic 1'
			],
			[
				'id' => 2,
				'title' => 'Topic 2'
			]
		];

		$this->repository
			->shouldReceive('getTop')
			->once()
			->andReturn($topics);

		$result = $this->service->getTop();

		$this->assertEquals(
			$topics,
			$result
		);
	}
}
