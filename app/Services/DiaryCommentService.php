<?php

namespace App\Services;

use App\Interfaces\DiaryInterface;
use App\Interfaces\DiaryCommentInterface;
use App\Models\DiaryComment;
use App\DTO\DiaryCommentDTO;
use App\Services\FileService;
use App\Models\User;

class DiaryCommentService
{
	public function __construct(
		protected DiaryCommentInterface $diaryCommentRepository,
		protected DiaryInterface $diaryRepository,
		protected FileService $fileService
	) {}

	/**
	 * get data for the index page of the comment
	 */
	public function getIndexData(int $id, int $perPage): array
	{
		return 
		[
			'diary'			=> $this->diaryRepository->getById($id),
			'comments'		=> $this->diaryCommentRepository->getByDiary($perPage, $id)
		];
	}

	/**
	 * get data for edit page of the comment 
	 */
	public function edit(int $id): DiaryComment
	{
		return $this->diaryCommentRepository->getByUserAndId($id, Auth()->id());
	}

	/**
	 * create a comment of the diary
	 */
	public function create(DiaryCommentDTO $dto): void
	{
		try {
			if ($dto->photo !== null)
			{
				$picture = $this->fileService->fotoUpload($dto->photo, 0, config('photos.diary_comment_folder'));
			}
			$picturePath = !empty($picture) ? $picture['unic_name'] : "0";

			$this->diaryCommentRepository->create([
				'diary_id'		=> $dto->diaryId,
				'user_id'		=> $dto->userId,
				'title'			=> $dto->title,
				'description'	=> $dto->description,
				'picture'	 	=> $picturePath,
				'create_time'	=> now()->timestamp
			]);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary Comment '.$e->getMessage());
		}
	}

	/**
	 * update a comment of the diary
	 */
	public function update(int $id, User $user, DiaryCommentDTO $dto): DiaryComment
	{
		$comment = $this->diaryCommentRepository->getByUserAndId($id, $user->id);

		try {
			$data = [
				'title'			=> $dto->title,
				'description'	=> $dto->description
			];

			if ($dto->photo !== null)
			{
				$picture = $this->fileService->fotoUpload($dto->photo, 0, config('photos.diary_comment_folder'));
				$data['picture'] = !empty($picture) ? $picture['unic_name'] : "0";
			}
			$this->diaryCommentRepository->update($id, $data);
			return $comment;

		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary Comment '.$e->getMessage());
		}
	}

	/**
	 * destroy a comment of the diary
	 */
	public function destroy(int $id, User $user): DiaryComment
	{
		$comment		= $this->diaryCommentRepository->getByUserAndId($id, $user->id);

		try {
			$this->fileService->remove($comment->picture_url);
			$this->diaryCommentRepository->delete($comment);
			return $comment;

		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment. '.$e->getMessage());
		}
	}
}