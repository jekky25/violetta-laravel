<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\Photo;
use App\Models\SexOrient;
use App\Models\SpeakLang;
use App\Models\Message;
use Database\Factories\PhotoFactory;
use Illuminate\Support\Facades\Auth;

trait hasSetupPrepare
{

	protected $countries	= null;
	protected $regions		= null;
	protected $cities		= null;
	protected $users		= null;
	protected $photos		= null;

	/**
	 * Set up Prepare
	 */
	protected function setUpPrepare(): void
	{
		PhotoFactory::resetPhoto();
		if (SexOrient::select('*')->count() === 0) {
			$this->sexOrient	= SexOrient::factory()->count(4)->sequence(
				['id' => '1', 'name' => 'Бисексуал'],
				['id' => '2', 'name' => 'Гетеросексуал'],
				['id' => '3', 'name' => 'Гомосексуал'],
				['id' => '4', 'name' => 'Транссексуал']
			)->create();
		}

		if (SpeakLang::select('*')->count() === 0) {
			$this->sexOrient	= SpeakLang::factory()->count(12)->sequence(
				['id' => '1', 'name' => 'Русский'],
				['id' => '2', 'name' => 'Украинский'],
				['id' => '3', 'name' => 'Белорусский'],
				['id' => '4', 'name' => 'Грузинский'],
				['id' => '5', 'name' => 'Английский'],
				['id' => '6', 'name' => 'Немецкий'],
				['id' => '7', 'name' => 'Французский'],
				['id' => '8', 'name' => 'Испанский'],
				['id' => '9', 'name' => 'Итальянский'],
				['id' => '10', 'name' => 'Китайский'],
				['id' => '11', 'name' => 'Японский'],
				['id' => '12', 'name' => 'Армянский'],
			)->create();
		}

		$this->countries	= Country::factory(10)->create();
		$this->regions		= Region::factory(20)->create();
		$this->cities		= City::factory(30)->create();
		$this->users		= User::factory(5)->create();
		$this->photos		= Photo::factory(5)->create();
		$this->actingAs($this->users[0]);
		$this->messages		= Message::factory(3)->create();
		$this->actingAs($this->users[1]);
		$this->messages		= Message::factory(2)->create();
		$this->actingAs($this->users[2]);
		$this->messages		= Message::factory(5)->create();
		Auth::logout();
	}
}
