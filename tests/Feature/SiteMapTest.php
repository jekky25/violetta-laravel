<?php

namespace Tests\Feature;
use Tests\TestCase;

class SiteMapTest extends TestCase
{
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