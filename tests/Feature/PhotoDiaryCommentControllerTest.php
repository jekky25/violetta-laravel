<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\PhotoDiaryCommentService;
use Tests\Traits\hasSetupPrepare;

class PhotoDiaryCommentControllerTest extends TestCase
{
	use hasSetupPrepare;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
		self::setUpPrepare();

        $this->service = Mockery::mock(
            PhotoDiaryCommentService::class
        );

        $this->app->instance(
            PhotoDiaryCommentService::class,
            $this->service
        );
    }

    public function test_destroy_deletes_comment_photo()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->service->shouldReceive('destroy')
            ->once()
            ->with(15, $user);

		$_SERVER['REQUEST_URI'] = route('ank.diary.comment.delete.photo.id', 15);
        $response = $this->delete($_SERVER['REQUEST_URI']);

        $response->assertRedirect(
            route('ank.diary.comment.edit.id', 15)
        );
    }
}