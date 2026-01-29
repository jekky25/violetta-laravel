<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CommentPhotoInterface;
use App\Requests\PhotoCommentRequest;

class PhotoCommentController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected CommentPhotoInterface $commentPhotoRepository
	) {}

	/**
	 * post pictures comments
	 * @param  PhotoCommentRequest  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function store(PhotoCommentRequest $request, $id)
	{
		$this->commentPhotoRepository->store($request->validated());
		return redirect()->back()
			->with('success', 'Сообщение успешно отправлено')
			->withInput();
	}
}
