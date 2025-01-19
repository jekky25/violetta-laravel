<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Name;

class NameTest extends TestCase
{
	use DatabaseMigrations;
	
	protected $names		= null;
	protected $namesCount	= 0;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		User::factory(20)->create();
		$this->names = Name::factory(50)->create();
		$this->namesCount = $this->names->count();
	}

	/**
	 * Get random id of the dreambook
	 * @return int
	 */
	protected function getRand()
	{
		return rand(0, $this->namesCount);
	}

	/**
	* Test a name main page
	*/
	public function test_name_main_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/names.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test a name gender page
	*/
	public function test_name_gender_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/names/men.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
		
		$_SERVER['REQUEST_URI'] = '/names/women.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test a name gender literal page
	*/
	public function test_name_gender_literal_page(): void
	{
		$ar = [
			'/names/men/3.html',
			'/names/men/4.html',
			'/names/women/3.html',
			'/names/women/4.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}


	/**
	* Test a name id page
	*/
	public function test_name_id_page(): void
	{
		$ar = [
			'/names/' . $this->getRand() . '.html',
			'/names/' . $this->getRand() . '.html',
			'/names/' . $this->getRand() . '.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
