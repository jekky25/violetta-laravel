<?php

namespace App\Repositories;

use App\Interfaces\DiaryCommentInterface;
use App\Models\DiaryComment;
use App\Services\FileService;

class DiaryCommentRepository implements DiaryCommentInterface {

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected FileService $fileService
	)
	{
	}

	/**
	* get comments by diaryId
	* @param  int $count
	* @param  int $diaryId
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByDiary($count, $diaryId)
	{
		return DiaryComment::select('*')->diaryId($diaryId)->with('user')->orderBy('create_time', 'desc')->paginate($count);
	}

	/**
	* get comments by commentId and userId
	*/
	public function getByUserAndId(int $id, int $userId): DiaryComment
	{
		return DiaryComment::select('*')->whereKey($id)->userId($userId)->firstOrFail();
	}

	/**
	 * create a comment of the diary
	 */
	public function create(array $data): void
	{
		try {
			DiaryComment::create($data);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary Comment '.$e->getMessage());
		}
	}

	/**
	* update a comment diary
	*/
	public function update(int $commentId, array $data): void {
		try {
			DiaryComment::find($commentId)->update($data);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update Diary Comment. '.$e->getMessage());
		}
	}

	/**
	* delete a comment diary
	* @param  DiaryComment $comment
	* @return void
	*/
	public function delete($comment) {
		try {
			DiaryComment::find($comment->id)->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment. '.$e->getMessage());
		}
	}
}