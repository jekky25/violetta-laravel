<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\DiaryInterface;
use App\Interfaces\DiaryCommentInterface;
use App\Services\DiaryCommentService;
use App\Services\MessageService;
use App\Services\FileService;

class PhotoDiaryCommentController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected DiaryCommentInterface $diaryCommentRepository,
		protected DiaryInterface $diaryRepository,
		protected MessageService $messageService,
		protected FileService $fileService,
		protected DiaryCommentService $service
	) {}

	/**
	 * delete the picture of the diary comment
	 * @param  int $id
	 * @return void
	 */
	public function destroyPhoto($id)
	{
		$user			= Auth::user();
		$this->diaryCommentRepository->getByUserAndId($id, $user->id);
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction	= route('ank.diary.comment.delete.photo.id', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * delete the picture of the diary comment confirm or cancel
	 * @param  Illuminate\Http\Request $request
	 * @param  int $id
	 * @return void
	 */

	public function destroyPhotoAction(Request $request, $id)
	{
		$user			= Auth::user();
		$comment 		= $this->diaryCommentRepository->getByUserAndId($id, $user->id);
		$arParams		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('ank.diary.comment.edit.id', $id);
		if (!empty($arParams['confirm'])) {
			$this->fileService->remove($comment->picture_url);
			$comment->picture			= 0;
			$request->title				= $comment->title;
			$request->description		= $comment->description;
			$this->diaryCommentRepository->update($comment, $request);
			return redirect()->route('ank.diary.comment.edit.id', $id);
		}
	}
}
