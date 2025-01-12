<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Diary;

class DiaryTest extends TestCase
{
	use DatabaseMigrations;
	
	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		User::factory(20)->create();
		Diary::factory(50)->create();
	}

	/** @test */
	public function diaries_page(): void
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
