<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;

class ScreenController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected ScreenInterface $screenRepository,
		protected CommentScreenInterface $commentScreenRepository
	)
	{
	}

	/**
	* Show the application dashboard.
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$screens			= $this->screenRepository->get(config('pagination.screens'));
		$page				= $screens->currentPage();
		return response()->view ('screensavers', 
		[
			'page'				=> $page,
			'screens'			=> $screens,
			'numScreens'		=> $screens->total()
		]);
	}

	/**
	* show a screensaver page and make download screensaver
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
		$screen				= $this->screenRepository->getById($id);
		$comments			= $this->commentScreenRepository->getByScrId($id);
		return response()->view ('screensavers_id', 
		[
			'screen'			=> $screen,
			'comments'			=> $comments
		]);

	}
}