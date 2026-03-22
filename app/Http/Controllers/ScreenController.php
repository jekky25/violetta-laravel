<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\Requests\ScreenRequest;
use App\Requests\ScreenDownloadRequest;

class ScreenController extends Controller
{
	const VAR_SCR = 1;
	const VAR_RAR = 2;

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

	/**
	* Create a screensaver post
	* @param ScreenRequest $request
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function store(ScreenRequest $request, $id)
	{
		$this->screenRepository->getById($id);
		$arParams = $request->validated();
		$this->commentScreenRepository->create($arParams);
		return redirect()->back()
						->with('success','Сообщение успешно отправлено')
						->withInput();
	}

	/**
	* Download a screensaver
	* @param ScreenDownloadRequest $request
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function download(ScreenDownloadRequest $request, $id)
	{
		$screen					= $this->screenRepository->getById($id);
		$screen->zakachka++;
		$this->screenRepository->update($screen);

		$fDown			= $request->get('f_download') == self::VAR_RAR ? self::VAR_RAR : self::VAR_SCR;
		if ($fDown == self::VAR_SCR)
		{
			$GetFile	= "screensavers/" . $screen->path_scr;
			$FileS		= $screen->name . ".scr";
			$header		= "Content-type: application charset=utf-8";
		} else
		{
			$GetFile	= "screensavers/" . $screen->path_rar;
			$FileS		= $screen->name . ".rar";
			$header		= "Content-type: application/x-rar-compressed charset=utf-8";
		}

		header($header);
		header("Content-Disposition: attachment; filename=". $FileS ."");
		header("Content-Length: " . FileSize($GetFile));

		ReadFile($GetFile);
		redirect(route('screensavers.id',$screen->id));
	}
}