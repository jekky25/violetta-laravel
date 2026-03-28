<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ScreenInterface;
use App\Requests\ScreenDownloadRequest;

class ScreenDownloadController extends Controller
{
	const VAR_SCR = 1;
	const VAR_RAR = 2;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected ScreenInterface $screenRepository
	)
	{
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