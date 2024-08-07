<?php

namespace Tests\Feature;
use Tests\TestCase;

class DreamBookTest extends TestCase
{
	/**
	* Test a dreambook main page
	*/
	public function test_dream_book_main_page(): void
	{
		$ar = [
			'dreambook.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test a dreambook literal page
	*/
	public function test_dream_book_literal_page(): void
	{
		$ar = [
			'/dreambook/op4.html',
			'/dreambook/op4.html?page=3',
			'/dreambook/op11.html',
			'/dreambook/op11.html?page=2',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test a dreambook id page
	*/
	public function test_dream_book_id_page(): void
	{
		$ar = [
			'/dreambook/560.html',
			'/dreambook/562.html',
			'/dreambook/563.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
