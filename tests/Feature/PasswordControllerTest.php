<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Traits\hasSetupPrepare;

class PasswordControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_user_can_update_password(): void
	{
		$oldPassword = 'old_password';
		$newPassword = 'new_password';
		$user = User::factory()->create([
			'password' => $oldPassword,
			'hash' => Hash::make($oldPassword)
		]);

		$this->actingAs($user);

		$_SERVER['REQUEST_URI'] = route('registration.edit.password.post');
		$response = $this->put($_SERVER['REQUEST_URI'], [
			'pass_old' => $oldPassword,
			'pass' => $newPassword,
			'pass_confirm' => $newPassword,
		]);

		$_SERVER['REQUEST_URI'] = route('registration.edit.password');
		$response->assertRedirect($_SERVER['REQUEST_URI']);
		$response->assertSessionHas('success');

		$user->refresh();

		$this->assertTrue(
			Hash::check($newPassword, $user->hash)
		);
	}
}
