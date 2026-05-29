<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Diary;
use App\Services\DiaryService;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\Traits\hasSetupPrepare;

class DiaryControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(DiaryService::class);

		$this->app->instance(
			DiaryService::class,
			$this->service
		);
	}

	public function test_index_returns_diaries_page()
	{
		$paginator = new LengthAwarePaginator([], 0, 10);

		$this->service->shouldReceive('index')
			->once()
			->with(config('pagination.diaries'))
			->andReturn($paginator);

		$_SERVER['REQUEST_URI'] = route('diaries');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertViewIs('diaries');

		$response->assertViewHas('diaries', $paginator);
	}

	public function test_my_diaries_returns_user_diaries()
	{
		$user = User::factory()->create();

		$paginator = new LengthAwarePaginator([], 0, 10);

		$this->actingAs($user);

		$this->service->shouldReceive('myDiaries')
			->once()
			->with(
				config('pagination.diaries_user'),
				$user
			)
			->andReturn($paginator);

		$_SERVER['REQUEST_URI'] = route('registration.edit.diary');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertViewIs('registration.diary');

		$response->assertViewHas('diaries', $paginator);
	}

	public function test_show_returns_diary_page()
	{
		$user = User::factory()->create();

		$paginator = new LengthAwarePaginator([], 0, 10);

		$data = [
			'userData' => $user,
			'diaries' => $paginator
		];

		$this->service->shouldReceive('getShowData')
			->once()
			->with(
				config('pagination.diaries_user'),
				$user->id
			)
			->andReturn($data);

		$_SERVER['REQUEST_URI'] = route('ank.diary.id', $user->id);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertViewIs('ankets.diary');

		$response->assertViewHas($data);
	}

	public function test_edit_returns_edit_page()
	{
		$user = User::factory()->create();

		$diary = Diary::factory()->create([
			'user_id' => $user->id
		]);

		$this->actingAs($user);

		$this->service->shouldReceive('edit')
			->once()
			->with($diary->id, $user)
			->andReturn($diary);

		$_SERVER['REQUEST_URI'] = route('ank.diary.edit.id', $diary->id);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertViewIs('ankets.diary_edit');

		$response->assertViewHas('diary', $diary);

		$response->assertViewHas('userData', $user);
	}

	public function test_store_creates_diary()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$this->service->shouldReceive('create')
			->once();

		$_SERVER['REQUEST_URI'] = route('ank.diary.add');
		$response = $this->post(
			$_SERVER['REQUEST_URI'],
			[
				'title' => 'Test',
				'description' => 'Text'
			]
		);

		$response->assertRedirect();
	}

	public function test_update_updates_diary()
	{
		$user = User::factory()->create();

		$diary = Diary::factory()->create([
			'user_id' => $user->id
		]);

		$this->actingAs($user);

		$this->service->shouldReceive('update')
			->once()
			->andReturn($diary);

		$_SERVER['REQUEST_URI'] = route('ank.diary.edit.update.id', $diary->id);
		$response = $this->put(
			$_SERVER['REQUEST_URI'],
			[
				'title' => 'Updated',
				'description' => 'Updated text'
			]
		);

		$response->assertRedirect(
			route('ank.diary.id', $diary->user_id)
		);
	}

	public function test_destroy_deletes_diary()
	{
		$user = User::factory()->create();

		$diary = Diary::factory()->create([
			'user_id' => $user->id
		]);

		$this->actingAs($user);

		$this->service->shouldReceive('destroy')
			->once()
			->with($diary->id, $user)
			->andReturn($diary);

		$_SERVER['REQUEST_URI'] = route('ank.diary.delete.id', $diary->id);
		$response = $this->delete(
			$_SERVER['REQUEST_URI']
		);

		$response->assertRedirect(
			route('ank.diary.id', $diary->user_id)
		);
	}
}
