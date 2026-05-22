<?php

namespace App\Services;

use App\DTO\ContactsDTO;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactsEmail;

class ContactsService
{
	/**
	 * Send an email over the contacts form
	 */
	public function store(ContactsDTO $dto): void
	{
		$data = $dto->toArray();
		Mail::mailer(config('mail.mail_mode'))
			->to(config('mail.email_main'))
			->send(new ContactsEmail($data));
	}
}
