<?php

namespace Tests\Feature;
use Tests\TestCase;

class DiaryTest extends TestCase
{
	/**
	* Test diaries page
	*/
	public function test_diaries_page(): void
	{
		$ar = [
			'/ank/diaries.html',
			'/ank/diaries.html?page=3',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
