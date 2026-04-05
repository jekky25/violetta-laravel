<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use App\Requests\PhotoRequest;
use App\Interfaces\PhotoInterface;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $userRepository,
		protected PhotoInterface $photoRepository
	) {}

	/**
	 * Show an edit page with the user pictures
	 * @return \Illuminate\Http\Response
	 */
	public function photo()
	{
		$user = $this->userRepository->getJustById(Auth::id(), ['photo']);
		return response()->view(
			'registration.photo',
			[
				'photos' => $user->photo
			]
		);
	}

	/**
	 * Add an user picture
	 * @param PhotoRequest $request
	 * @return void
	 */
	public function photoStore(PhotoRequest $request)
	{
		$this->photoRepository->store(Auth::user(), $request->validated());
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Show an edit picture page
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function editPhoto($id)
	{
		$photo			= $this->photoRepository->getByIdAndUserId($id, Auth::id());
		return response()->view(
			'registration.photo_edit',
			[
				'photo' => $photo
			]
		);
	}

	/**
	 * Reupload an user picture
	 * @param  PhotoRequest  $request
	 * @param int $id
	 * @return void
	 */
	public function editPhotoUpdate(PhotoRequest $request, $id)
	{
		$photo			= $this->photoRepository->getByIdAndUserId($id, Auth::id());
		$this->photoRepository->update($photo, $request->validated());
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Delete an user picture
	 * @param int $id
	 * @return void
	 */
	public function destroyPhoto($id)
	{
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction	= route('registration.edit.photo.delete', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * Delete an user picture
	 * @param  PhotoRequest $request
	 * @param int $id
	 * @return void
	 */
	public function destroyPhotoAction(PhotoRequest $request, $id)
	{
		$photo			= $this->photoRepository->getByIdAndUserId($id, Auth::id());
		$arParams 		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('registration.edit.photo');
		if (!empty($arParams['confirm'])) {
			$this->photoRepository->destroyPhoto($photo);
			return redirect()->route('registration.edit.photo')->with('success', 'Информация сохранена.');
		}
	}
}