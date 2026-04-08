<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PasswordService;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Traits\hasSetupPrepare;
use Mockery;

class PasswordServiceTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_update_hashes_password_and_calls_repository(): void
	{
		$user = User::factory()->make();

		$repository = Mockery::mock(UserRepository::class);

		$repository->shouldReceive('update')
			->once()
			->withArgs(function ($u, $data) use ($user) {
				return $u === $user
					&& $data['password'] === 'secret123'
					&& Hash::check('secret123', $data['hash']);
			});

		$service = new PasswordService($repository);

		$service->update($user, [
			'pass' => 'secret123'
		]);

		$this->assertTrue(true);
	}
}
