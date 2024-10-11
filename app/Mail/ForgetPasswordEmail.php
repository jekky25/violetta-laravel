<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordEmail extends Mailable
{
	use Queueable, SerializesModels;

	private	$template				= 'mails.pass';
	private	$templateText			= 'mails.txt.pass';
	public	$subject				= 'Запрос пароля на www.avioletta.ru';
	private	$siteUrl				= 'www.avioletta.ru';
	private	$siteUrlWithProtocol	= 'http://www.avioletta.ru';
	private	$data					= [];
	private	$params					= [];

	/**
	* Create a new message instance.
	*
	* @return void
	*/
	public function __construct($user)
	{
		$data					= new \stdClass();
		$data->login			= $user->user_login;
		$data->password			= $user->user_password;
		$data->sitename			= '<a href="' . $this->siteUrlWithProtocol . '">' . $this->siteUrl . '</a>';
		$data->sitenameNoTags	= $this->siteUrl;
		$this->data = $data;
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
			]);
	}
}