<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Diary;
use Tests\Traits\hasSetupPrepare;

class DiaryTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;
	
	/**
	 * Set up variables
	 */
	protected function setUp() :void
	{
		parent::setUp();
		self::setUpPrepare();
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
