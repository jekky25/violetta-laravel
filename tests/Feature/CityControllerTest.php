<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Models\City;

class CityControllerTest extends TestCase
{
	use hasSetupPrepare;
	
	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_it_returns_cities_by_region()
	{
		$city = City::factory()->create([
			'region_id' => 5,
			'name' => 'City X'
		]);

		$response = $this->get('/api/cities/' . $city->region_id);
		$response->assertStatus(200);
		$response->assertJsonFragment([
			'name' => 'City X'
		]);
	}
}
