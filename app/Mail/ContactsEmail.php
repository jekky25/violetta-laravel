<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactsEmail extends Mailable
{
	use Queueable, SerializesModels;

	private	$template		= 'mails.contacts';
	private	$templateText	= 'mails.txt.contacts';
	public	$subject		= 'Контакты';
	private	$data			= [];
	private	$params			= [];

	/**
	* Create a new message instance.
	*
	* @return void
	*/
	public function __construct($arParams)
	{
		$data					= new \stdClass();
		$data->name				= $arParams['name'];
		$data->organization		= $arParams['organization'];
		$data->email			= $arParams['mail'];
		$data->description		= $arParams['description'];
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