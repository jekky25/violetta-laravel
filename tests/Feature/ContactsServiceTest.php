<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Services\ContactsService;
use App\Mail\ContactsEmail;
use App\DTO\ContactsDTO;
use Tests\Traits\hasSetupPrepare;

class ContactsServiceTest extends TestCase
{
	use hasSetupPrepare;

    protected ContactsService $service;

    protected function setUp(): void
    {
        parent::setUp();
		self::setUpPrepare();

        $this->service = new ContactsService();
    }

    public function test_store_sends_email()
    {
        Mail::fake();

        $dto = new ContactsDTO(
            name: 'John',
            email: 'john@test.com',
			organization: 'bla bla bla',
            description: 'Hello'
        );

        $this->service->store($dto);

        Mail::assertSent(
            ContactsEmail::class,
            function ($mail) {
                return true;
            }
        );
    }
}