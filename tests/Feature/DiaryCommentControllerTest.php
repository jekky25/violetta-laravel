<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Diary;
use App\Models\DiaryComment;
use App\Services\DiaryCommentService;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Pagination\LengthAwarePaginator;

class DiaryCommentControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		$this->service = Mockery::mock(DiaryCommentService::class);

		$this->app->instance(
			DiaryCommentService::class,
			$this->service
		);
	}

	public function test_index_returns_comments_view()
	{
		$diary = Diary::factory()->make();

		$comments = new LengthAwarePaginator(
			items: [],
			total: 0,
			perPage: 10,
			currentPage: 1
		);

		$this->service->shouldReceive('getIndexData')
			->once()
			->with(1, config('pagination.comments_diary'))
			->andReturn([
				'diary' => $diary,
				'comments' => $comments
			]);

		$_SERVER['REQUEST_URI'] = route('ank.diary.comments', 1);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();
		$response->assertViewIs('ankets.comments');
	}

	public function test_edit_returns_edit_view()
	{
		$user = User::factory()->create();

		$comment = DiaryComment::factory()->create();

		$this->actingAs($user);

		$this->service->shouldReceive('edit')
			->once()
			->with($comment->id)
			->andReturn($comment);

		$_SERVER['REQUEST_URI'] = route('ank.diary.comment.edit.id', $comment->id);
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();
		$response->assertViewIs(
			'ankets.diary_comment_edit'
		);
	}

	public function test_store_creates_comment()
	{
		$user = User::factory()->create();

		$this->actingAs($user);

		$this->service->shouldReceive('create')
			->once();

		$_SERVER['REQUEST_URI'] = route('ank.diary.comment.add', 1);

		$response = $this->post(
			$_SERVER['REQUEST_URI'],
			[
				'title' => 'Test',
				'description' => 'Description'
			]
		);

		$response->assertSessionHas('success');
	}

	public function test_update_updates_comment()
	{
		$user = User::factory()->create();

		$comment = DiaryComment::factory()->make([
			'diary_id' => 5
		]);

		$this->actingAs($user);

		$this->service->shouldReceive('update')
			->once()
			->andReturn($comment);

		$_SERVER['REQUEST_URI'] = route('ank.diary.comment.update.id', 10);

		$response = $this->put(
			$_SERVER['REQUEST_URI'],
			[
				'title' => 'Updated',
				'description' => 'Updated text'
			]
		);

		$response->assertRedirect(
			route('ank.diary.comments', 5)
		);
	}

	public function test_destroy_deletes_comment()
	{
		$user = User::factory()->create();

		$comment = DiaryComment::factory()->make([
			'diary_id' => 7
		]);

		$this->actingAs($user);

		$this->service->shouldReceive('destroy')
			->once()
			->with(10, $user)
			->andReturn($comment);

		$_SERVER['REQUEST_URI'] = route('ank.diary.comment.delete.id', 10);
		$response = $this->delete(
			$_SERVER['REQUEST_URI']
		);

		$response->assertRedirect(
			route('ank.diary.comments', 7)
		);
	}
}
