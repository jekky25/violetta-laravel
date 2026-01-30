<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Diary;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Support\Facades\Auth;

class DiaryTest extends TestCase
{
	use hasSetupPrepare;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$user = User::select('id')->first();
		Auth::loginUsingId($user->id);
		Diary::factory(50)->create();
	}

	public function test_diaries_page(): void
	{
		$ar = [
			'/ank/diaries.html',
			'/ank/diaries.html?page=3',
		];

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}
}
