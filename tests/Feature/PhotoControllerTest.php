<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Photo;
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

    public function test_show_main_redirects_to_photo()
    {
        $user = User::factory()->create();
        $photo = Photo::factory()->create(['user_id' => $user->id, 'main_picture' => 1]);
        $this->actingAs($user);
        $this->service->shouldReceive('getMainPhoto')
            ->once()
            ->with($user->id)
            ->andReturn($photo);

        $_SERVER['REQUEST_URI'] = route('ank.photo.id', $user->id);
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertRedirect(route('ank.photo.photo_id', $photo->id));
    }

    public function test_show_main_returns_404_if_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->service->shouldReceive('getMainPhoto')
            ->with(10000)
            ->once()
            ->andReturn(null);

        $_SERVER['REQUEST_URI'] = route('ank.photo.id', 10000);
        $response = $this->get($_SERVER['REQUEST_URI']);
        $response->assertStatus(404);
    }

    public function test_show_returns_view()
    {
        $user = User::factory()->create();
        $photo = Photo::factory()->create(['user_id' => $user->id, 'main_picture' => 1]);

        $data = [
            'photo' => $photo,
            'userData' => $user
        ];

        $this->service->shouldReceive('getPhotoPageData')
            ->once()
            ->andReturn($data);

        $this->actingAs($user);

        $_SERVER['REQUEST_URI'] = route('ank.photo.photo_id', $photo->id);
        $response = $this->get($_SERVER['REQUEST_URI']);

        $response->assertStatus(200)
            ->assertViewIs('ankets.photo')
            ->assertViewHas('photo')
            ->assertViewHas('userData');
    }
}