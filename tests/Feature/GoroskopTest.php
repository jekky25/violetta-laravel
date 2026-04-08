<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\Horoscope;
use Tests\Traits\hasSetupPrepare;

class GoroskopTest extends TestCase
{
	use hasSetupPrepare;

	protected $horoscopes		= null;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->horoscopes = Horoscope::factory(50)->create();
	}

	/**
	* Test a horoscope main page
	*/
	public function test_horoscope_main_page(): void
	{
		$_SERVER['REQUEST_URI'] = route('horoscope');
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test horoscope id pages
	*/
	public function test_horoscope_id_page(): void
	{
		$_SERVER['REQUEST_URI'] = route('horoscope.id', 2);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);

		$_SERVER['REQUEST_URI'] = route('horoscope.id', 3);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test horoscope type pages
	*/
	public function test_horoscope_type_page(): void
	{
		$ar = [2,3,4,5];

		foreach ($ar as $id)
		{
			$_SERVER['REQUEST_URI'] = route('horoscope.op', $id);
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
