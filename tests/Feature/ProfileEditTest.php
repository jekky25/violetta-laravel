<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Services\DataService;

class ProfileEditTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

	protected $data = [
		'login'					=> 'test25',
		'password'				=> '123456',
		'password_second'		=> '123456',
		'name'					=> 'test',
		'email'					=> 'test@test.ru',
		'sex'					=> MEN,
		'birth_date'			=> '1983-05-20',
		'country_id'			=> 141,
		'region_id'				=> 526,
		'city_id'				=> 1458,
		'conditions'			=> 1,
		'description'			=> '',
		'partner_description'	=> '',
		'ip'					=> '127.0.0.1',
		'remember_token'		=> 'assdddaddasd',
		'speak_lang'			=> '',
		'partner_body'			=> '',
		'partner_languages'		=> '',
		'partner_education'		=> '',
		'partner_smoke'			=> '',
		'partner_alcohol'		=> '',
		'targets'				=> '',
		'interests'				=> '',
		'phone'					=> '',
		'url'					=> '',
	];

	protected $userRepository;
	protected $dataService;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->userRepository	= new UserRepository;
		$this->dataService		= new DataService;;
	}

	/** @test */
	public function check_register_user(): void
	{
		$this->userRepository->create($this->data);
		$user	= $this->userRepository->getByLogin($this->data['login']);
		$this->assertEquals($user->login, 			$this->data['login']);
		$this->assertEquals($user->password,	 	$this->data['password']);
		$this->assertEquals($user->name, 			$this->data['name']);
		$this->assertEquals($user->email, 			$this->data['email']);
		$this->assertEquals($user->sex, 			$this->data['sex']);
		$this->assertEquals($user->birth_date,		$this->data['birth_date']);
		$this->assertEquals($user->country_id, 		$this->data['country_id']);
		$this->assertEquals($user->region_id, 		$this->data['region_id']);
		$this->assertEquals($user->city_id, 		$this->data['city_id']);
	}
}
