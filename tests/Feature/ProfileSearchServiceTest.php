<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Interfaces\UserInterface;
use App\Services\SearchService;
use App\Fields\SearchField;
use App\Services\ProfileSearchService;
use App\Filters\UserFilter;
use Mockery;

class ProfileSearchServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_search_returns_expected_structure()
	{
		$repo = Mockery::mock(UserInterface::class);
		$searchService = \Mockery::mock(SearchService::class);
		$fields = Mockery::mock(SearchField::class);

		$service = new ProfileSearchService($repo, $searchService);

		$data = ['sex' => 'men'];

		$repo->shouldReceive('getBySearch')
			->once()
			->with(Mockery::type(UserFilter::class), $data)
			->andReturn(collect(['anket1']));

		$searchService->shouldReceive('getSearchText')
			->once()
			->with($data)
			->andReturn('мужчины');

		$fields->shouldReceive('get')
			->once()
			->andReturn(['field1']);

		$result = $service->search(
			$data,
			Mockery::mock(UserFilter::class),
			$fields
		);

		$this->assertArrayHasKey('fields', $result);
		$this->assertArrayHasKey('critsSearch', $result);
		$this->assertArrayHasKey('ankets', $result);
	}

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}
}
