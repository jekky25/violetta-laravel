<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery;
use App\Services\ContactsService;
use Tests\Traits\hasSetupPrepare;

class ContactsControllerTest extends TestCase
{
	use hasSetupPrepare;

	protected $service;

	protected function setUp(): void
	{
		parent::setUp();
		self::setUpPrepare();

		config(['services.recaptcha.enabled' => false]);

		$this->service = Mockery::mock(ContactsService::class);

		$this->app->instance(
			ContactsService::class,
			$this->service
		);
	}

	public function test_index_returns_contacts_view()
	{
		$_SERVER['REQUEST_URI'] = route('contacts');
		$response = $this->get($_SERVER['REQUEST_URI']);

		$response->assertOk();

		$response->assertViewIs('contacts');
	}

	public function test_store_sends_message_and_redirects()
	{
		$data = [
			'name' => 'John',
			'email' => 'john@test.com',
			'message' => 'Hello',
		];

		$this->service->shouldReceive('store')->once();

		$_SERVER['REQUEST_URI'] = route('contacts.post');

		$response = $this->post($_SERVER['REQUEST_URI'], $data);

		$response->assertRedirect(route('contacts'));

		$response->assertSessionHas('success');
	}
}
