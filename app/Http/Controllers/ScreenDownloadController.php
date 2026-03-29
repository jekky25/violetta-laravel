<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\ScreenDownloadRequest;
use App\Services\ScreenDownloadService;
use App\Enums\ScreenSaverType;
use App\DTO\DownloadScreenDTO;

class ScreenDownloadController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(protected ScreenDownloadService $service) {}

	/**
	* Download a screensaver
	* @param ScreenDownloadRequest $request
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function download(ScreenDownloadRequest $request, int $id)
	{
		$dto = new DownloadScreenDTO(
			screenId: $id,
			type: ScreenSaverType::toView($request->validated()['f_download'])
    	);
	    return $this->service->download($dto);
	}
}