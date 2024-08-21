<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Requests\ContactsRequest;
use App\Mail\Email;

class ContactsController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
	}

	/**
	* show the feedback page
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		return response()->view ('contacts');
	}

	/**
	* post a message from the feedback page
	* @param  ContactsRequest $request
	* @return \Illuminate\Http\Response
	*/
	public function post(ContactsRequest $request)
	{
		$arParams 				= $request->post();
		$oMail 					= new \stdClass();
		$oMail->emailTo 		= config('mail.email_main');
		$oMail->emailFrom 		= config('mail.email_main');
		$oMail->template 		= 'mails.contacts';
		$oMail->templateText 	= 'mails.txt.contacts';
		$oMail->name 			= $arParams['name'];
		$oMail->organization 	= $arParams['organization'];
		$oMail->email 			= $arParams['mail'];
		$oMail->description 	= $arParams['description'];
		$oMail->subject			= "Контакты";
		Mail::mailer(config('mail.mail_mode'))
		->to($oMail->emailTo)
		->send(new Email($oMail));

		return redirect()->route(Route::currentRouteName())->with('success','Ваше сообщение было отослано в службу поддержки. Спасибо!');
	}
}