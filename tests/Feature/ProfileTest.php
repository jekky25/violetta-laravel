<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Photo;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;

class ProfileTest extends TestCase
{
	use DatabaseMigrations, hasSetupPrepare;

	protected $maxItems		= 3;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
	}

	/**
	 * create user
	 * @param int $sex
	 * @return User
	 */
	protected function createUser($sex)
	{
		$user = User::factory(
			[
				'top100' 			=> time(),
				'confirm_email'		=> 1,
				'sex'				=> $sex
			]
		)->create();
		$photos	 = Photo::factory(3)->create(
			['user_id' => $user->user_id]
		);
		$user->update(['photos_count' => $photos->count()]);
		$user->save();
		return $user;
	}

	/**
	 * Test a profile id page
	 */
	public function test_profile_id_page(): void
	{
		$i = 0;
		$ar = [];
		foreach ($this->users as $user) {
			$i++;
			if ($i > $this->maxItems) break;
			$ar[] = '/ank/' . $user->user_id . '/';
		}

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	 * Test a full profile id page
	 */
	public function test_full_profile_id_page(): void
	{
		$i = 0;
		$ar = [];
		foreach ($this->users as $user) {
			$i++;
			if ($i > $this->maxItems) break;
			$ar[] = '/ank/f/' . $user->user_id . '/';
		}

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	 * Test a page with profiles
	 */
	public function test_profiles_page(): void
	{
		$ar = [
			'/ankets/',
		];

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	 * Test a page with profiles who has a birthday today
	 */
	public function test_birthday_profiles_page(): void
	{
		$ar = [
			'/birthday_search/',
		];

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	 * Test a page with the most popular profiles
	 */
	public function test_popular_profiles_page(): void
	{
		$ar = [
			'/population_search/',
		];

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/**
	 * Test a top100 pages
	 */
	public function test_top100_profiles_page(): void
	{
		$ar = [
			'bestankets/women/',
			'bestankets/men/',
		];

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}


	/**
	 * Test a picture profile page
	 */
	public function test_picture_profile_id_page(): void
	{
		$user	= User::get()->random();
		Auth::loginUsingId($user->user_id);
		$userId = Photo::get()->random()->user_id;
		$ar = [
			'ank/photo/' . $userId . '.html',
		];
		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(302);
		}

		$i = 0;
		$ar = [];
		foreach ($this->photos as $photo) {
			$i++;
			if ($i > $this->maxItems) break;
			$ar[] = 'ank/f/photo_' . $photo->id . '/';
		}

		foreach ($ar as $item) {
			$_SERVER['REQUEST_URI'] = $item;
			$response = $this->get($_SERVER['REQUEST_URI']);
			$response->assertStatus(200);
		}
	}

	/** @test */
	public function get_the_best_of_top100_profile(): void
	{
		$this->userRepository = new UserRepository;

		$this->createUser(WOMEN);
		$this->createUser(MEN);

		$ankets = $this->userRepository->getTop100(WOMEN, 1);
		$this->assertInstanceOf(User::class, $ankets);

		$ankets = $this->userRepository->getTop100(WOMEN, 2);
		$this->assertInstanceOf(User::class, $ankets);
	}
}
