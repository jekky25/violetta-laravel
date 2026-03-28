<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\HoroscopeService;
use Illuminate\View\View;

class HoroscopeController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		private HoroscopeService $service
	) {}

	/**
	* Show the page with horoscopes
	* @return View
	*/
	public function index()
	{
		return view('horoscope', ['data' => $this->service->getIndexData()]);
	}

	/**
	* show a horoscope page by id
	* @param  int $id
	* @return View
	*/
	public function show(int $id)
	{
		return view('horoscope', ['data' => $this->service->getItemData($id)]);
	}

	/**
	* show the page with horoscope types
	* @param  int $id
	* @return View
	*/
	public function showType(int $id = 0)
	{
		return view('horoscope', ['data' => $this->service->getTypeData($id)]);
	}
}