<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\Helpers\Helper;
use App\Requests\ScreenRequest;
use App\Requests\ScreenDownloadRequest;

class ScreenController extends Controller
{
	public $countPerPage 	= 6;

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
		$screens 			= $this->screenRepository->get($this->countPerPage);
		$page 				= $screens->currentPage();
		$pagination 		= Helper::preparePagination ($screens->toArray()['links']);
		return response()->view ('screensavers', 
		[
			'page'				=> $page,
			'screens'			=> $screens,
			'pagination'		=> $pagination,
			'numScreens'		=> $screens->total()
		]);
    }

	/**
	* show a screensaver page and make download screensaver
	* @param Illuminate\Http\Request $request
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function getItem(Request $request, $id)
	{
		$screen 			= $this->screenRepository->getById($id);
		if (empty ($screen)) abort(404);

		$screen->size_scr 	= Helper::formatFileSize($screen->size_scr);
		$screen->size_rar 	= Helper::formatFileSize($screen->size_rar);
		$comments 			= $this->commentScreenRepository->getByScrId($id);

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
		$screen				= $this->screenRepository->getById($id);
		if (empty ($screen)) abort(404);
		$arParams = $request->post();
		$description =  str_replace("\'", "''", $arParams['description']);
		$user = Auth::user();
		if (empty ($user))
		{
			return redirect()->route('login');
		}
		$user = $user->load(['visits']);

		$aFields = [
			'scr_id'		=> $screen->id,
			'name'			=> $user->user_name,
			'email' 		=> $user->user_mail,
			'description'	=> $description,
			'time'			=> time()
		];

		$this->commentScreenRepository->create($aFields);
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
		$screen				= $this->screenRepository->getById($id);
		if (empty ($screen)) abort(404);
		$screen->zakachka++;
		$screen->save();

		$fDown     = $request->get('f_download') == 2 ? 2 : 1;
		if ($fDown == 1)
		{
			$GetFile 	= "screensavers/" . $screen->path_scr;
			$FileS 		= $screen->name . ".scr";
			$header		= "Content-type: application charset=utf-8";
		} else
		{
			$GetFile 	= "screensavers/" . $screen->path_rar;
			$FileS 		= $screen->name . ".rar";
			$header		= "Content-type: application/x-rar-compressed charset=utf-8";
		}

		header($header);
		header("Content-Disposition: attachment; filename=". $FileS ."");
		header("Content-Length: " . FileSize($GetFile));

		ReadFile($GetFile);
		redirect(route('screensavers.id',$screen->id));
	}
}

