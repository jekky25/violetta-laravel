<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ScreenService;
use Illuminate\View\View;

class ScreenController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(protected ScreenService $service) {}

	/**
	* Show the application dashboard.
	* @return View
	*/
	public function index(): View
	{
		return view('screensavers', [ 'screens' => $this->service->getList(config('pagination.screens')) ]);
	}

	/**
	* show a screensaver page and make download screensaver
	* @param int $id
	* @return View
	*/
	public function show(int $id): View
	{
		return view('screensavers_id', ['data' => $this->service->showPage($id)]);
	}
}