<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class MainPageTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		User::factory(20)->create();
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
