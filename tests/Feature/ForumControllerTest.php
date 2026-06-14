<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\ForumService;
use Tests\Traits\hasSetupPrepare;

class ForumControllerTest extends TestCase
{
	use hasSetupPrepare;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        self::setUpPrepare();

        $this->service = Mockery::mock(
            ForumService::class
        );

        $this->app->instance(
            ForumService::class,
            $this->service
        );
    }

    public function test_index_returns_false()
    {
        $controller = app(
            \App\Http\Controllers\ForumController::class
        );

        $this->assertFalse(
            $controller->index()
        );
    }

    public function test_get_top_returns_resource_collection()
    {
        $topics = [
           (object) [
                'topic_title' => 'Topic 1',
                'topic_id' => 1,
                'forum_id' => 2
            ],
           (object) [
                'topic_title' => 'Topic 2',
                'topic_id' => 2,
                'forum_id' => 2
            ]
        ];

        $this->service
            ->shouldReceive('getTop')
            ->once()
            ->andReturn($topics);

        $response = $this->getJson(
            route('forum.get.top')
        );

        $response->assertOk();

        $response->assertJsonCount(2);
    }
}
