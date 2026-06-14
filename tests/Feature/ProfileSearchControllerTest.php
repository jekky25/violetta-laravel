<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Fields\SearchField;
use App\Services\DataService;
use App\Services\FormatService;
use App\Models\Country;

class ProfileSearchControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $userRepository;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->userRepository		= new UserRepository;
	}

	public function test_search_returns_view()
	{
		$service = \Mockery::mock(\App\Services\ProfileSearchService::class);
		$this->app->instance(\App\Services\ProfileSearchService::class, $service);

		$country = \Mockery::mock(\App\Interfaces\CountryInterface::class);

		$country->shouldReceive('getAll')
			->once()
			->andReturn(Country::select('*')->orderBy('name', 'asc')->get());

		$data = new DataService();
		$format = new FormatService();

		$fields = new SearchField($country, $data, $format);

		$data = [
			'fields' => $fields->get(),
			'critsSearch' => 'что-то',
			'ankets' => collect(),
		];

		$service->shouldReceive('search')
			->once()
			->andReturn($data);

		$_SERVER['REQUEST_URI'] = route('search');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewIs('ankets.search');
	}
}