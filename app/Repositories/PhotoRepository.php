<?php

namespace App\Repositories;

use App\Interfaces\PhotoInterface;
use App\Models\Photo;
use App\Models\User;
use App\Services\FileService;
use \Illuminate\Database\Eloquent\Collection;

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
	 */
	public function getId(): int
	{
		return ($this->id > 0 ? $this->id : 0);
	}

	/**
	 * get count all user pictures
	 */
	public function getCount(): int
	{
		return Photo::select('id')->count();
	}

	/**
	 * get pucture by id
	 */
	public function getById(int $id): Photo
	{
		return Photo::select('*')->whereKey($id)->firstOrFail();
	}

	/**
	 * get pucture by id and user_id
	 */
	public function getByIdAndUserId(int $id, int $userId): Photo
	{
		return Photo::select('*')->whereKey($id)->userId($userId)->firstOrFail();
	}

	/**
	 * get the first pucture by userId
	 */
	public function getFirstByUserId(int $id): Photo
	{
		return Photo::select('*')->userId($id)->first();
	}

	/**
	 * get all puctures by userId
	 */
	public function getAllByUserId(int $id): Collection
	{
		return Photo::select('*')->userId($id)->get();
	}

	/**
	 * get main photo of the user
	 */
	public function getMainPhotoByUserId(int $userId): ?Photo
	{
		return Photo::select('*')->userId($userId)->orderBy('main_picture', 'desc')->first();
	}

	/**
	 * create a photo
	 */
	public function create(array $data): Photo
	{
		try {
			$photo = Photo::create($data);
			return $photo;
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Photo ' . $e->getMessage());
		}
	}

	/**
	 * update a photo
	 */
	public function update(Photo $photo, array $params): void
	{
		try {
			Photo::find($photo->id)->update($params);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update a Photo ' . $e->getMessage());
		}
	}

	/**
	 * destroy all pictures by userId
	 */
	public function destroyAllByUser(User $user): bool
	{
		if (count($user->photo) == 0) return false;
		foreach ($user->photo as $item) {
			$this->destroyPhoto($item);
		}
		return true;
	}

	/**
	 * destroy a picture from the model
	 * @param  Photo $photo
	 * @return void
	 */
	public function destroyPhoto(Photo $photo)
	{
		$photo->delete();
		return true;
	}
}
