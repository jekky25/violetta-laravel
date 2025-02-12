<?php

namespace Tests\Feature;
use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Arr;

class SearchTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	/**
	* Test a name main page
	*/
	public function test_search_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/search/';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/** @test */
	public function search_with_params(): void
	{
		$country = Country::factory()->create(
			[
				'id'						=> 141,
				'name'						=> 'Россия'
			]);

		$city = City::factory()->create(
			[
				'id'						=> 2953,
				'country_id'				=> $country->id,
				'region_id'					=> 1,
				'name'						=> 'Москва'
			]);
$ar[] = [
			"sex" => 1,
			"find_sex" => 2,
			"photo" => true,
			"age_min" => 22,
			"age_max" => 31,
			"height_min" => 150,
			"height_max" => 185,
			"weight_min" => 35,
			"weight_max" => 89,
			"country" => $country->id,
			"region" => 1,
			"city" => $city->id,
			"body" => 0,
			"hair_type" => 0,
			"anket_per_page" => 10,
			"eyes" => 0
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = '/search/?' . Arr::query($item);
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
