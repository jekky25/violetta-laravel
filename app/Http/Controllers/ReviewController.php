<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ReviewController extends Controller
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
	* Show the review page.
	* @return void
	*/
	public function index()
	{
		abort (404);
	}
}