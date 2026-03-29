<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CommentScreenService;
use App\Requests\ScreenRequest;
use App\DTO\CreateCommentScreenDTO;

class CommentScreenController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(protected CommentScreenService $service) {}

	/**
	* Create a screensaver post
	* @param ScreenRequest $request
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function store(ScreenRequest $request, int $id)
	{
		$dto = CreateCommentScreenDTO::fromRequest(
	        $id,
    	    $request->user(),
	        $request->validated()
    	);
		$this->service->store($dto);
		return redirect()->back()
						->with('success','Сообщение успешно отправлено')
						->withInput();
	}
}