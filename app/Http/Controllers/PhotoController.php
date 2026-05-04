<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Requests\PhotoRequest;
use App\Services\PhotoService;
use App\DTO\CreatePhotoDTO;
use App\DTO\UpdatePhotoDTO;

class PhotoController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected PhotoService $service
	) {}

	/**
	 * Show an edit page with the user pictures
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->view('registration.photo', [
			'photos' => $this->service->allByUser(request()->user())
		]);
	}

	/**
	 * Show a page with user pictures
	 * @param  int $userId
	 * @return \Illuminate\Http\Response
	 */
	public function showMain($userId)
	{
    	$photo = $this->service->getMainPhoto($userId);
	    if (!$photo) abort(404);
		return redirect()->route('ank.photo.photo_id', $photo->id);
	}

	/**
	 * Show a user photo
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $id)
	{
		return view('ankets.photo', $this->service->getPhotoPageData($id, Auth()->user()));
	}

	/**
	 * Add an user picture
	 * @param PhotoRequest $request
	 * @return RedirectResponse
	 */
	public function store(PhotoRequest $request)
	{
		$this->service->create(request()->user(), CreatePhotoDTO::fromRequest($request));
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Show an edit picture page
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return response()->view(
			'registration.photo_edit',
			[
				'photo' => $this->service->edit($id, request()->user())
			]
		);
	}

	/**
	 * Reupload an user picture
	 * @param  PhotoRequest  $request
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function update(PhotoRequest $request, $id)
	{
		$this->service->update($id, request()->user(), UpdatePhotoDTO::fromRequest($request));
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Delete an user picture
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function destroy($id)
	{
		$this->service->destroy($id, request()->user());
		return redirect()->route('registration.edit.photo')->with('success', 'Информация сохранена.');
		
	}
}