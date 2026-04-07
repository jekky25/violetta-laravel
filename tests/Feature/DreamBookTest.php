<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\DreamBook;
use Tests\Traits\hasSetupPrepare;

class DreamBookTest extends TestCase
{
	use hasSetupPrepare;

	protected $dreamBooks		= null;
	protected $dreamBooksCount	= 0;

	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->dreamBooks = DreamBook::factory(50)->create();
		$this->dreamBooksCount = $this->dreamBooks->count();
	}

	/**
	 * Get random id of the dreambook
	 * @return int
	 */
	protected function getRand()
	{
		return rand(0, ($this->dreamBooksCount-1));
	}

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
			'/dreambook/' . $this->dreamBooks[$this->getRand()]->id . '.html',
			'/dreambook/' . $this->dreamBooks[$this->getRand()]->id . '.html',
			'/dreambook/' . $this->dreamBooks[$this->getRand()]->id . '.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	* Test no exist dreambook id page
	*/
	public function test_dream_book_no_exist_id_page(): void
	{
		$ar = [
			'/dreambook/560000.html',
		];

		foreach ($ar as $item)
		{
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(404);
		}
	}
}
