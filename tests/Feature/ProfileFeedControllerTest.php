<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Models\User;

use Mockery;

class ProfileFeedControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected function setUp(): void
	{
		parent::$migrated = true;
		parent::setUp();
		self::setUpPrepare();
	}

	public function test_birthday_returns_view()
	{
		$service = \Mockery::mock(\App\Services\ProfileFeedService::class);
		$this->app->instance(\App\Services\ProfileFeedService::class, $service);

		$profiles = User::select(['id', 'rating', 'name', 'birth_date', 'city_id', 'sex'])
			->active()
			->with('city')
			->with('photo')
			->orderBy('top100', 'desc')
			->paginate(20);

		$service->shouldReceive('getBirthday')
			->once()
			->andReturn($profiles);

		$_SERVER['REQUEST_URI'] = route('birthday_search');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewIs('ankets.birthday_search');
		$response->assertViewHas('ankets', $profiles);
	}
}