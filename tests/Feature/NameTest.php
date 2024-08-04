<?php

namespace Tests\Feature;
use Tests\TestCase;

class NameTest extends TestCase
{
	/**
	* Test a name main page
	*/
	public function test_name_main_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/names.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}
}
