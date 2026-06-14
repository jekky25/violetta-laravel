<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Interfaces\UserInterface;
use App\Services\ProfileFeedService;
use App\Filters\UserBirthdayFilter;
use App\Requests\UserBirthdayRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class ProfileFeedServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_getBirthday_returns_paginator()
	{
		$repo = Mockery::mock(UserInterface::class);
		$service = new ProfileFeedService($repo);

		$filter = Mockery::mock(UserBirthdayFilter::class);
		$request = Mockery::mock(UserBirthdayRequest::class);
		$paginator = Mockery::mock(LengthAwarePaginator::class);

		$repo->shouldReceive('getBySearch')
			->once()
			->with($filter, $request)
			->andReturn($paginator);

		$result = $service->getBirthday($filter, $request);

		$this->assertSame($paginator, $result);
	}
}