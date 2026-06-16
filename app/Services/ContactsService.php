<?php

namespace App\Services;

use App\DTO\ContactsDTO;
use App\Jobs\SendFeedBackJob;

class ContactsService
{
	/**
	 * Send an email over the contacts form
	 */
	public function store(ContactsDTO $dto): void
	{
		SendFeedBackJob::dispatch($dto->toArray());
	}
}
