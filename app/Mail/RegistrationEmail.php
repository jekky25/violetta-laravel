<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable
{
	use Queueable, SerializesModels;

	private	$template				= 'mails.register';
	private	$templateText			= 'mails.txt.register';
	public	$subject				= 'Регистрация на www.avioletta.ru';
	private	$siteUrl				= 'www.avioletta.ru';
	private	$siteUrlWithProtocol	= 'http://www.avioletta.ru';
	private	$data					= [];

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($user)
	{
		$data					= new \stdClass();
		$data->login			= $user->login;
		$data->password			= $user->password;
		$data->id				= $user->user_id;
		$data->code				= $user->submit_code;
		$data->sitename			= '<a href="' . $this->siteUrlWithProtocol . '">' . $this->siteUrl . '</a>';
		$data->sitenameNoTags	= $this->siteUrl;
		$this->data				= $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->from(config('mail.email_main'))
			->view($this->template)
			->subject($this->subject)
			->text($this->templateText)
			->with(
				[
					'data' => $this->data
				]
			);
	}
}
