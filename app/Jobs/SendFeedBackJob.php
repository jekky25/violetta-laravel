<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactsEmail;

class SendFeedBackJob implements ShouldQueue
{
	use Queueable;

	private $data = [];

	/**
	 * Create a new job instance.
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		Mail::mailer(config('mail.mail_mode'))
			->to(config('mail.email_main'))
			->send(new ContactsEmail($this->data));
	}
}
