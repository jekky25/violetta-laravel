<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Interfaces\DiaryInterface;
use App\Interfaces\UserInterface;
use App\Requests\DiaryRequest;
use App\Services\MessageService;
use App\Services\FileService;

class DiaryController extends Controller
{
	public $countPerPage		= 10;
	public static $diaryPerPage	= 10;

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
	)
	{
	}

	/**
	* show the page with diaries
	*/
	public function index()
	{
		$diaries		= $this->diaryRepository->getAll($this->countPerPage);
		return response()->view ('diaries', 
		[
			'diaries'				=> $diaries
		]);
	}

	/**
	* add an user diary
	* @param  DiaryRequest  $request
	* @return \Illuminate\Http\Response
	*/
	public function store (DiaryRequest $request)
	{
		$user 			= Auth::user();
		$this->diaryRepository->store($request->validated(user_id: $user->user_id));
		return redirect()->back()
		->with('success','Дневник успешно добавлен')
		->withInput();
	}

	/**
	* show an user diary page
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function show ($id)
	{
		$anket 	= $this->userRepository->getById ($id);
		if (empty ($anket->photo)) abort (404);

		$diaries = $this->diaryRepository->getByUser (self::$diaryPerPage, $id);
		if (count ($diaries) == 0) abort (404);
		$page 				= $diaries->currentPage();
		return response()->view ('ankets.diary',
		[
			'userData'		=> $anket,
			'diaries'		=> $diaries,
			'page'			=> $page
		]);
	}

	/**
	* show edit diary page
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function edit ($id)
	{
		$user			= Auth::user();
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		$diary->user_dnevnik_title	= old('title')	 		? old('title') 			: stripslashes ($diary->dnevniki_title);
		$diary->user_dnevnik_text	= old('description') 	? old('description') 	: $diary->dnevniki_text;
		return response()->view ('ankets.diary_edit',
		[
			'userData'		=> $user,
			'diary'			=> $diary,
		]);
	}

	/**
	* update diary 
	* @param  DiaryRequest  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function update (DiaryRequest $request, $id)
	{
		$user			= Auth::user();
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		$this->diaryRepository->update($diary, $request);
		return redirect()->route('ank.diary.id', $user->user_id)
			->with('success','Дневник был обновлен')
			->withInput();
	}

		/**
	* delete the user diary
	* @param  int $id
	* @return void
	*/
	public function destroy ($id)
	{
		$user			= Auth::user();
		$this->diaryRepository->getByUserAndId($id, $user->user_id);
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить эту запись<br /><br />';
		$confirmAction	= route ('ank.diary.delete.id', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	* delete the user diary confirm or cansel
	* @param  DiaryRequest  $request
	* @param  int $id
	* @return void
	*/
	public function destroyAction (DiaryRequest $request, $id)
	{
		$user			= Auth::user();
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		$arParams		= $request->post();
		if ( !empty($arParams['cancel']) ) return redirect()->route ('ank.diary.id', $user->user_id);
		if ( !empty($arParams['confirm']) ) {
			$this->diaryRepository->delete($diary);
			return redirect()->route ('ank.diary.id', $user->user_id);
		}
	}

	/**
	* delete the picture of the diary
	* @param  int $id
	* @return void
	*/
	public function destroyPhoto ($id)
	{
		$user			= Auth::user();
		$this->diaryRepository->getByUserAndId($id, $user->user_id);
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction	= route ('ank.diary.delete.photo.id', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	* delete the picture of the diary confirm or cansel
	* @param  Illuminate\Http\Request $request
	* @param  int $id
	* @return void
	*/
	public function destroyPhotoAction (Request $request, $id)
	{
		$user			= Auth::user();
		$diary			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		$arParams		= $request->post();
		if ( !empty($arParams['cancel']) ) return redirect()->route ('ank.diary.edit.id', $id);
		if ( !empty($arParams['confirm']) ) {
			$this->fileService->remove($diary->dnevniki_picture_url);
			$diary->dnevniki_picture = 0;
			$request->title				= $diary->dnevniki_title;
			$request->description		= $diary->dnevniki_text;
			$this->diaryRepository->update($diary, $request);
			return redirect()->route ('ank.diary.edit.id', $id);
		}
	}
}