<?php

namespace Tests\Feature;
use Tests\TestCase;

class MainPageTest extends TestCase
{
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
