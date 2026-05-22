<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Requests\ContactsRequest;
use App\Services\ContactsService;
use App\DTO\ContactsDTO;

class ContactsController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(protected ContactsService $service) {}

	/**
	* show the feedback page
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		return response()->view('contacts');
	}

	/**
	* post a message from the feedback page
	* @param  ContactsRequest $request
	* @return \Illuminate\Http\Response
	*/
	public function store(ContactsRequest $request)
	{
		$this->service->store(ContactsDTO::fromRequest($request));
		return redirect()->route(Route::currentRouteName())->with('success','Ваше сообщение было отослано в службу поддержки. Спасибо!');
	}
}