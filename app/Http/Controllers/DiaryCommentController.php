<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\DiaryInterface;
use App\Interfaces\DiaryCommentInterface;
use App\Requests\DiaryCommentRequest;
use App\Services\MessageService;
use App\Services\FileService;

class DiaryCommentController extends Controller
{
	public static $diaryCommentsPerPage	= 20;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected DiaryCommentInterface $diaryCommentRepository,
		protected DiaryInterface $diaryRepository,
		protected MessageService $messageService,
		protected FileService $fileService
	) {}

	/**
	 * show a comments diary page
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function index($id)
	{
		$comments 	= $this->diaryCommentRepository->getByDiary(self::$diaryCommentsPerPage, $id);
		$diary 		= $this->diaryRepository->getById($id);
		return response()->view(
			'ankets.comments',
			[
				'userData'		=> $diary->user,
				'diary'			=> $diary,
				'comments'		=> $comments
			]
		);
	}

	/**
	 * add a comment of the diary
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function store(DiaryCommentRequest $request, $id)
	{
		$user 			= Auth::user();
		$this->diaryCommentRepository->store($request->validated(id: $id, user_id: $user->id));
		return redirect()->back()
			->with('success', 'Комментарий успешно добавлен')
			->withInput();
	}

	/**
	 * show an edit comment page and update the comment
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user						= Auth::user();
		$comment					= $this->diaryCommentRepository->getByUserAndId($id, $user->id);
		return response()->view(
			'ankets.diary_comment_edit',
			[
				'userData'		=> $user,
				'comment'		=> $comment,
			]
		);
	}

	/**
	 * update an edit comment page and update the comment
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(DiaryCommentRequest $request, $id)
	{
		$user						= Auth::user();
		$comment					= $this->diaryCommentRepository->getByUserAndId($id, $user->id);
		$this->diaryCommentRepository->update($comment, $request);
		return redirect()->route('ank.diary.comments', $comment->diary_id)
			->with('success', 'Комментарий был обновлен')
			->withInput();
	}

	/**
	 * delete a comment of the diary
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return void
	 */
	public function destroy($id)
	{
		$user			= Auth::user();
		$this->diaryCommentRepository->getByUserAndId($id, $user->id);
		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить эту запись<br /><br />';
		$confirmAction 	= route('ank.diary.comment.delete.id', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * delete a comment of the diary confirm or cancel
	 * @param  DiaryCommentRequest $request
	 * @param  int $id
	 * @return void
	 */
	public function destroyAction(DiaryCommentRequest $request, $id)
	{
		$user			= Auth::user();
		$comment		= $this->diaryCommentRepository->getByUserAndId($id, $user->id);
		$arParams		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('ank.diary.comments', $comment->diary_id);
		if (!empty($arParams['confirm'])) {
			$this->diaryCommentRepository->delete($comment);
			return redirect()->route('ank.diary.comments', $comment->diary_id);
		}
	}

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
