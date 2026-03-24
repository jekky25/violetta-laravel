<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\DreamBookInterface;
use App\Services\DreamBookService;

class DreamBookController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected DreamBookInterface $dreamBookRepository,
		private DreamBookService $service
	)
	{
	}

	/**
	* Show the page with dreambooks
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function index(int $id = 1)
	{
		$words = $this->service->getList($id);
		return view('dreambooks', 
		[
			'dreamBookLiterals'		=> $this->service->getLiterals(),
			'words'					=> $words,
			'page'					=> $words->currentPage()
		]);
	}

	/**
	* Show the page with a dreambook
	* @param  \Illuminate\Http\Request  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function show(int $id)
	{
		return view('dreambooks_id', [
            'dreamBookLiterals' => $this->service->getLiterals(),
            'dreambook' => $this->service->getItem($id)
        ]);		
	}
}