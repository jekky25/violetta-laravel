<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Requests\ContactsRequest;
use App\Mail\ContactsEmail;

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
		$arParams 				= $request->validated();
		Mail::mailer(config('mail.mail_mode'))
		->to(config('mail.email_main'))
		->send(new ContactsEmail($arParams));
		return redirect()->route(Route::currentRouteName())->with('success','Ваше сообщение было отослано в службу поддержки. Спасибо!');
	}
}