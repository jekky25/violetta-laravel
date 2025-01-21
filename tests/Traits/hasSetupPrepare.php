<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\Photo;
use Database\Factories\PhotoFactory;

trait hasSetupPrepare {
	
	protected $countries	= null;
	protected $regions		= null;
	protected $cities		= null;
	protected $users		= null;
	protected $photos		= null;

	/**
	 * Set up Prepare
	 */
	protected function setUpPrepare() :void
	{
		parent::setUp();
		PhotoFactory::resetPhoto();
		$this->countries	= Country::factory(10)->create();
		$this->regions		= Region::factory(20)->create();
		$this->cities		= City::factory(30)->create();
		$this->users		= User::factory(3)->create();
		$this->photos		= Photo::factory(5)->create();
	}
}