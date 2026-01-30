<?php

namespace Tests\Feature;
use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;

class MainPageTest extends TestCase
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

	/**
	* Test a main page
	*/
	public function test_main_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}
}
