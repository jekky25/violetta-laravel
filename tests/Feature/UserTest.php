<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	/** @test */
	public function remove_user(): void
	{
		$_SERVER['REQUEST_URI'] = 'registration/delete/confirm/';
		$user = User::factory()->create();
		Auth::loginUsingId($user->user_id);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertRedirectToRoute('registration.delete');
	}
}
