<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\ContactsRequest;
use App\Services\ContactsService;
use App\DTO\ContactsDTO;
use Illuminate\View\View;

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
	* @return View
	*/
	public function index()
	{
		return view('contacts');
	}

	/**
	* post a message from the feedback page
	* @param  ContactsRequest $request
	* @return \Illuminate\Http\Response
	*/
	public function store(ContactsRequest $request)
	{
		$this->service->store(ContactsDTO::fromRequest($request));
		return redirect()->route('contacts')->with('success', 'Ваше сообщение было отослано в службу поддержки. Спасибо!');
	}
}