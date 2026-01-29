<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPrivMessageEmail extends Mailable
{
	use Queueable, SerializesModels;

	private	$template		= 'mails.notify';
	private	$templateText	= 'mails.txt.notify';
	private	$data			= [];
	private	$params			= [];
	private $siteUrl				= 'www.avioletta.ru';
	private $siteUrlWithProtocol	= 'http://www.avioletta.ru';
	public	$subject		= 'Вам пришло новое сообщение на www.avioletta.ru';

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($anket)
	{
		$data					= new \stdClass();
		$data->sitename 		= '<a href="' . $this->siteUrlWithProtocol . '">' . $this->siteUrl . '</a>';
		$data->sitenameNoTags	= $this->siteUrl;
		$data->name 			= $anket->name;
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
				]
			);
	}
}
