<?php

namespace Tests\Feature;
use Tests\TestCase;

class GoroskopTest extends TestCase
{
	/**
	* Test a goroskop main page
	*/
	public function test_goroskop_main_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/goroskop.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test goroskop id pages
	*/
	public function test_goroskop_id_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/goroskop/2.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);

		$_SERVER['REQUEST_URI'] = '/goroskop/3.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}

	/**
	* Test goroskop type pages
	*/
	public function test_goroskop_type_page(): void
	{
		$_SERVER['REQUEST_URI'] = '/goroskop/op2.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);

		$_SERVER['REQUEST_URI'] = '/goroskop/op3.html';
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
	}
}
