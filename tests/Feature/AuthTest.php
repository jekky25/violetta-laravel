<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
	use hasSetupPrepare;

	protected $userRepository;
	protected $testLogin		= 'test';
	protected $testPassword		= '12345';

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::$migrated = true;
		$this->testLogin .=  time();
		$this->testPassword .=  time();
		$this->userRepository		= new UserRepository;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_check_auth(): void
	{
		$user = User::factory(
			[
				'top100' 			=> time(),
				'confirm_email'		=> 1,
				'sex'				=> WOMEN,
				'login'				=> $this->testLogin,
				'password'			=> $this->testPassword,
				'hash'				=> Hash::make($this->testPassword)
			]
		)->create();

		$user2			= $this->userRepository->getByLoginAndPass($user->login, $user->password);
		$this->assertInstanceOf(User::class, $user2);
		$this->assertSame($user->id, $user2->id);
	}
}
