<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
	)
	{
	}

	/**
	* post pictures comments
	* @param  PhotoCommentRequest  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function store (PhotoCommentRequest $request, $id)
	{
		$user 			= Auth::user();
		$this->commentPhotoRepository->store($request->validated(foto_id: $id, user_id: $user->user_id));
		return redirect()->back()
		->with('success','Сообщение успешно отправлено')
		->withInput();
	}
}