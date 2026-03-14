<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;
use App\Repositories\CountryRepository;
use App\Repositories\RegionRepository;
use App\Repositories\CityRepository;
use App\Services\DataService;
use App\Services\FormatService;
use App\Models\User;

class MessageTest extends TestCase
{
	use hasSetupPrepare;

	protected $userRepository;
	protected $countryRepository;
	protected $regionRepository;
	protected $cityRepository;
	protected $dataService;
	protected $formService;

	/**
	 * Set up variables
	 */
	protected function setUp(): void
	{
		parent::setUp();
		
		self::setUpPrepare();
		$this->userRepository		= new UserRepository;
		$this->dataService			= new DataService;
		$this->formService			= new FormatService;
		$this->countryRepository	= new CountryRepository;
		$this->regionRepository		= new RegionRepository;
		$this->cityRepository		= new CityRepository;

		$user = User::factory()->create();
		$this->user = $user;
	}

	public function test_get_private_messages_page(): void
	{
		$url = $_SERVER['REQUEST_URI'] = route('privmsg');
		$response = $this->get($url);
		$response->assertRedirectToRoute('login');

		$response = $this->actingAs($this->user)->get($url);
		$response->assertStatus(200);
	}
}
