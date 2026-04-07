<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Tests\Traits\hasSetupPrepare;

class AccountControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_user_can_delete_account(): void
	{
		$user = User::factory()->create();
		$this->actingAs($user);

		$_SERVER['REQUEST_URI'] = route('registration.delete');
		$response = $this->delete($_SERVER['REQUEST_URI']);
		$_SERVER['REQUEST_URI'] = route('home');
		$response->assertRedirect($_SERVER['REQUEST_URI']);
		$response->assertSessionHas('success');
		$this->assertDatabaseMissing('users_news', [
			'id' => $user->id,
		]);

		$this->assertGuest();
	}
}
