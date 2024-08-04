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
			'/names/61.html',
			'/names/62.html',
			'/names/63.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
