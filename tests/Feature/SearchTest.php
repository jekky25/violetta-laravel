<?php

namespace Tests\Feature;
use Tests\TestCase;

class SearchTest extends TestCase
{
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
