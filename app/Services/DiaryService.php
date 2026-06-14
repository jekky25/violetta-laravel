<?php

namespace App\Services;

use App\DTO\DiaryDTO;
use App\Interfaces\DiaryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\FileService;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\Diary;

class DiaryService
{
	public function __construct(
		protected DiaryInterface $repository,
		protected UserInterface $userRepository,
		protected FileService $fileService
	) {}

	/**
	 * get diaries for the index page
	 */
	public function index(int $perPage): LengthAwarePaginator
	{
		return $this->repository->getAll($perPage);
	}

	/**
	 * get diaries of the auth user
	 */
	public function myDiaries(int $perPage, User $user): LengthAwarePaginator
	{
		return $this->repository->getByUser($perPage, $user->id);
	}

	/**
	 * create a diary
	 */
	public function create(DiaryDTO $dto, User $user): void
	{
		try {
			if ($dto->photo !== null)
			{
				$picture = $this->fileService->fotoUpload($dto->photo, 0, config('photos.diary_folder'));
			}
			$picturePath = !empty($picture) ? $picture['unic_name'] : "0";

			$this->repository->create([
				'user_id'		=> $user->id,
				'title'			=> $dto->title,
				'description'	=> $dto->description,
				'picture'	 	=> $picturePath,
				'create_time'	=> now()->timestamp
			]
			);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary ' . $e->getMessage());
		}
	}

	/**
	 * get data for the show page
	 */
	public function getShowData(int $perPage, int $id): array
	{
		$profile = $this->userRepository->getById($id);
		$diaries = $this->repository->getByUser($perPage, $id);
		if ($profile === null || count($diaries) == 0) abort(404);

		return [
				'userData'		=> $profile,
				'diaries'		=> $diaries
			];
	}

	/**
	 * get data for an edit page
	 */
	public function edit(int $id, User $user): Diary
	{
		return $this->repository->getByUserAndId($id, $user->id);
	}

	/**
	 * update a diary
	 */
	public function update(int $id, User $user, DiaryDTO $dto): Diary
	{
		$diary = $this->repository->getByUserAndId($id, $user->id);
		try {
			$data = [
				'title'			=> $dto->title,
				'description'	=> $dto->description
			];

			if ($dto->photo !== null)
			{
				$picture = $this->fileService->fotoUpload($dto->photo, 0, config('photos.diary_folder'));
				$data['picture'] = !empty($picture) ? $picture['unic_name'] : "0";
			}
			
			$this->repository->update($id, $data);
			return $diary;
		} catch (\Exception $e) {
			throw new \Exception('Failed to update Diary. ' . $e->getMessage());
		}
	}

	/**
	 * destroy a diary
	 */
	public function destroy(int $id, User $user): Diary
	{
		$diary = $this->repository->getByUserAndId($id, $user->id);

		try {
			$this->fileService->remove($diary->picture_url);
			$this->repository->delete($diary);
			return $diary;
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary. '.$e->getMessage());
		}
	}
}