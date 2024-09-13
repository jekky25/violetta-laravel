<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\DreamBookInterface;

class SiteController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected DreamBookInterface $dreamBookRepository
	)
	{
	}

	/**
	* Show the application dashboard.
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$dreamBook = $this->dreamBookRepository->getAll();
		return response()->view ('site_map', 
		[
			'dreamBook' => $dreamBook
		]);
	}
}