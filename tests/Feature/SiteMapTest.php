<?php

namespace Tests\Feature;
use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;

class SiteMapTest extends TestCase
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
	* Test a site map page
	*/
	public function test_site_map_page(): void
	{
		$ar = [
			'/sitemap/',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}