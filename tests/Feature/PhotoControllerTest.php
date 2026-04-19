<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Services\PhotoService;
use Tests\Traits\hasSetupPrepare;
use Illuminate\Http\UploadedFile;

class PhotoControllerTest extends TestCase
{
    use hasSetupPrepare;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
		self::setUpPrepare();

        $this->service = Mockery::mock(PhotoService::class);
        $this->app->instance(PhotoService::class, $this->service);
    }

    public function test_it_shows_index_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->service
            ->shouldReceive('allByUser')
            ->once()
            ->andReturn(collect());

        $_SERVER['REQUEST_URI'] = route('registration.edit.photo');
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200);
    }

    public function test_it_stores_photo()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->service
            ->shouldReceive('create')
            ->once();

        $file = UploadedFile::fake()->image('photo.jpg');

        $_SERVER['REQUEST_URI'] = route('registration.edit.photo.post');
        $response = $this->post($_SERVER['REQUEST_URI'], [
            'photo' => $file
        ]);

        $response->assertRedirect();
    }

    public function test_it_updates_photo()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->service
            ->shouldReceive('update')
            ->once();

        $file = UploadedFile::fake()->image('photo.jpg');

        $_SERVER['REQUEST_URI'] = route('registration.edit.photo.edit.post', 1);
        $response = $this->put($_SERVER['REQUEST_URI'], [
            'photo' => $file
        ]);

        $response->assertRedirect();
    }

    public function test_it_deletes_photo()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->service
            ->shouldReceive('destroy')
            ->once();

        $_SERVER['REQUEST_URI'] = route('registration.edit.photo.delete.action', 1);
        $response = $this->delete($_SERVER['REQUEST_URI']);

        $response->assertRedirect();
    }
}