<?php

namespace App\Repositories;

use App\Interfaces\PhotoInterface;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\FileService;

class PhotoRepository implements PhotoInterface
{
	private $id;
	protected FileService $fileService;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->fileService = new FileService;
	}

	/**
	 * get id
	 * @return integer;
	 */
	public function getId()
	{
		return ($this->id > 0 ? $this->id : 0);
	}

	/**
	 * get count all user pictures
	 * @return int
	 */
	public function getCount()
	{
		return Photo::select('id')->count();
	}

	/**
	 * get pucture by id
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getById($id)
	{
		$item = Photo::select('*')
			->where('id', $id)
			->firstOrFail();
		return $item;
	}

	/**
	 * get pucture by id and user_id
	 * @param  int $id
	 * @param  int $userId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByIdAndUserId($id, $userId)
	{
		$item = Photo::select('*')
			->where('id', $id)
			->where('user_id', $userId)
			->firstOrFail();
		return $item;
	}

	/**
	 * get the first pucture by userId
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getFirstByUserId($id)
	{
		$item = Photo::select('*')
			->where('user_id', $id)
			->first();
		return $item;
	}

	/**
	 * get all puctures by userId
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAllByUserId($id)
	{
		$item = Photo::select('*')
			->where('user_id', $id)
			->get();
		return $item;
	}

	/**
	 * create a photo
	 * @param  array $request
	 * @return void
	 */
	public function create($request)
	{
		try {
			$photo = Photo::create($request);
			$this->id = $photo->getKey();
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo ' . $e->getMessage());
		}
	}

	/**
	 * create a photo by user
	 * @param  User $user
	 * @param  array $params
	 * @return void
	 */
	public function store($user, $params)
	{
		try {
			$user->refresh_date				= date("Y-m-d");
			$countPhoto						= count($user->photo);
			$aFields = [
				'main_picture'				=> $countPhoto > 0 ? 0 : 1,
				'create_time'				=> 0,
				'user_id'					=> $user->user_id
			];
			$this->create($aFields);
			$photoId = $this->getId();
			$params['photo_link']->nameForInsert = $photoId . '.' . $params['photo_link']->extension();
			$this->fileService->fotoUpload($params['photo_link'], 1000, 'fotos_new/');
			$countPhoto++;
			$countPhoto = $countPhoto > 5 ? 5 : $countPhoto;
			User::find($user->user_id)->update(
				[
					'photos_count'			=> $countPhoto,
					'refresh_date'			=> date("Y-m-d"),
					'refresh_date_t'		=> time(),
					'session_time'			=> time(),
					'lastvisit'				=> time()
				]
			);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo ' . $e->getMessage());
		}
	}

	/**
	 * update a photo
	 * @param  Photo $photo
	 * @param  array $params
	 * @return void
	 */
	public function update($photo, $params)
	{
		try {
			$photo->create_time = time();
			$photo->update();
			$params['photo_link']->nameForInsert = $photo->id . '.' . $params['photo_link']->extension();
			$this->fileService->fotoUpload($params['photo_link'], 1000, 'fotos_new/');
			User::find($photo->user_id)->update(
				[
					'refresh_date'		 	=> date("Y-m-d"),
					'refresh_date_t'	 	=> time(),
					'session_time' 			=> time(),
					'lastvisit' 			=> time()
				]
			);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update a Photo ' . $e->getMessage());
		}
	}

	/**
	 * destroy all pictures by userId
	 * @param  User $user
	 * @return void
	 */
	public function destroyAllByUser(User $user)
	{
		if (count($user->photo) == 0) return false;
		foreach ($user->photo as $item) {
			$this->destroyPhoto($item);
		}
	}

	/**
	 * destroy a picture from the model
	 * @param  Photo $photo
	 * @return void
	 */
	public function destroyPhoto(Photo $photo)
	{
		$user 			= Auth::user();
		$id 			= $photo->id;
		$this->fileService->fotoDelete($id);
		$isPortret = $photo->main_picture == 1 ? 1 : 0;
		$photo->delete();
		if ($isPortret) {
			$photo = $this->getFirstByUserId($user->user_id);
			if (!empty($photo)) {
				$photo->main_picture = 1;
				$photo->update();
			}
		}
		$user->refresh_date 		= date("Y-m-d");
		$user->refresh_date_t 		= time();
		$user->session_time 		= time();
		$user->lastvisit	 		= time();
		$user->photos_count 		= $this->getAllByUserId($user->user_id)->count();
		$user->update();

		return true;
	}
}
