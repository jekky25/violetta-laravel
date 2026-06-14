<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\hasSetupPrepare;
use App\Repositories\UserRepository;

class ProfileBrowseControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $userRepository;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();
		$this->userRepository		= new UserRepository;
	}

	public function test_get_list_returns_view()
	{
		$service = \Mockery::mock(\App\Services\ProfileBrowseService::class);

		$this->app->instance(\App\Services\ProfileBrowseService::class, $service);

		$data = [
			'ankets' => $this->userRepository->newFaces(config('pagination.profiles_under_menu')),
			'sex' => 'men',
			'popSex' => 'Анкеты'
			];

		$service->shouldReceive('getList')
			->once()
			->andReturn($data);

		$_SERVER['REQUEST_URI'] = route('ankets');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewIs('ankets.id');
		$response->assertViewHas($data);
	}

	public function test_popular_returns_view()
	{
		$service = \Mockery::mock(\App\Services\ProfileBrowseService::class);
		$this->app->instance(\App\Services\ProfileBrowseService::class, $service);

		$data = ['ankets' => $this->userRepository->newFaces(config('pagination.profiles_under_menu')), 'popSex' => 'мужчины'];

		$service->shouldReceive('getPopular')
			->once()
			->andReturn($data);

		$_SERVER['REQUEST_URI'] = route('population_search', ['men']);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(200);
		$response->assertViewIs('ankets.popular_search');
		$response->assertViewHas($data);
	}

	public function test_best_returns_view()
	{
		$service = \Mockery::mock(\App\Services\ProfileBrowseService::class);
		$this->app->instance(\App\Services\ProfileBrowseService::class, $service);

		$sex = 1;
		$perPage = 20;

		$user = \App\Models\User::factory(['sex' => $sex])->create();
		$data = [
			'user_id'					=> $user->id,
			'main_picture'				=> 1,
			'create_time'				=> time()
		];

		\App\Models\Photo::factory($data)->create();
		$user->photos_count = 1;
		$user->save();

		$user = \App\Models\User::factory(['sex' => $sex])->create();
		$data = [
			'user_id'					=> $user->id,
			'main_picture'				=> 1,
			'create_time'				=> time()
		];
		
		\App\Models\Photo::factory($data)->create();
		$user->photos_count = 1;
		$user->save();

		$data = [
			'ankets' => $this->userRepository->getTop100($sex, $perPage),
			'titleId' => 'Лучшие парни',
			'user' => $user];

		$service->shouldReceive('getBest')
			->once()
			->andReturn($data);

		$this->actingAs($user);

		$_SERVER['REQUEST_URI'] = route('bestankets.sex', ['men']);
		$response = $this->get($_SERVER['REQUEST_URI']);
		$response->assertStatus(200);
		$response->assertViewIs('ankets.best');
		$response->assertViewHas($data);
	}

	public function test_top100_returns_resource()
	{
		$service = \Mockery::mock(\App\Services\ProfileBrowseService::class);
		$this->app->instance(\App\Services\ProfileBrowseService::class, $service);

		$user = \App\Models\User::factory()->create();

		$data = [
				'user_id'					=> $user->id,
				'main_picture'				=> 1,
				'create_time'				=> time()
			];
		$photo = \App\Models\Photo::factory($data)->create();
		$user->photo = $photo;

		$service->shouldReceive('getTop100')
			->once()
			->andReturn($user);

		$_SERVER['REQUEST_URI'] = route('profile.get.top100', ['1']);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertStatus(201);
	}
}
