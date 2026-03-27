<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Region;
use Tests\Traits\hasSetupPrepare;

class RegionControllerTest extends TestCase
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

	public function test_it_returns_regions_by_country()
	{
		Region::factory()->create([
			'country_id' => 1,
			'name' => 'Region A'
		]);
		$response = $this->get('/api/regions/1');
		$response->assertStatus(200);
		$response->assertJsonFragment([
			'name' => 'Region A'
		]);
	}
}
