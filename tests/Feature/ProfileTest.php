<?php

namespace Tests\Feature;
use Tests\TestCase;

class ProfileTest extends TestCase
{
	/**
	* Test a name gender literal page
	*/
	public function test_profile_id_page(): void
	{
		$ar = [
			'/ank/1/',
			'/ank/3/',
			'/ank/4/',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
