<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{	$data = $this->data;
		$subject = !empty($data->subject) ? $data->subject : '';
		return $this->from(config('mail.email_main'))
		->view($data->template)
		->subject($subject)
		->text($data->templateText)
		->with(
			[
				'data' => $this->data
			])
			/*
			->attach(public_path('/images').'/demo.jpg', [
				'as' => 'demo.jpg',
				'mime' => 'image/jpeg',
			])*/;
	}
}
