<?php

namespace Tests\Feature;
use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
}
