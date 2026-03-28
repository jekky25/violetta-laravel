<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ScreenInterface;
use App\Interfaces\CommentScreenInterface;
use App\Requests\ScreenRequest;

class CommentScreenController extends Controller
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
}