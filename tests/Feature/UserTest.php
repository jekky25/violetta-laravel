<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserTest extends TestCase
{
	use hasSetupPrepare;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_remove_user(): void
	{
		$_SERVER['REQUEST_URI'] = 'registration/delete/confirm/';
		$user = User::factory()->create();
		Auth::loginUsingId($user->id);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertRedirectToRoute('registration.delete');
	}
}
