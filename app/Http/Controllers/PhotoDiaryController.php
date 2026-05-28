<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Interfaces\DiaryInterface;
use App\Interfaces\UserInterface;
use App\Services\MessageService;
use App\Services\FileService;

class PhotoDiaryController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(
		protected DiaryInterface $diaryRepository,
		protected UserInterface $userRepository,
		protected MessageService $messageService,
		protected FileService $fileService
	) {}

	/**
	 * delete the picture of the diary
	 * @param  int $id
	 * @return void
	 */
	public function destroyPhoto($id)
	{
		$user			= Auth::user();
		$this->diaryRepository->getByUserAndId($id, $user->id);
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction	= route('ank.diary.delete.photo.id', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * delete the picture of the diary confirm or cansel
	 * @param  Illuminate\Http\Request $request
	 * @param  int $id
	 * @return void
	 */
	public function destroyPhotoAction(Request $request, $id)
	{
		$user			= Auth::user();
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->id);
		$arParams		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('ank.diary.edit.id', $id);
		if (!empty($arParams['confirm'])) {
			$this->fileService->remove($diary->picture_url);
			$diary->picture = 0;
			$request->title				= $diary->title;
			$request->description		= $diary->description;
			$this->diaryRepository->update($diary, $request);
			return redirect()->route('ank.diary.edit.id', $id);
		}
	}
}
