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
	* @param  int $id
	* @param  int $userId
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByUserAndId($id, $userId)
	{
		return DiaryComment::select('*')->whereKey($id)->userId('user_id', $userId)->firstOrFail();
	}

	/**
	* store a comment diary
	* @param  array $request
	* @return void
	*/	
	public function store($request) {
		try {
			if (!empty($request['photo_link']))
			{
				$picture = $this->fileService->fotoUpload($request['photo_link'], 0, 'img/dnev_comment/');
			}
			$request['picture']	= !empty($picture) ? $picture : "0";
			DiaryComment::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary Comment '.$e->getMessage());
		}
	}

	/**
	* update a comment diary
	* @param  DiaryComment $comment
	* @param  DiaryCommentRequest $request
	* @return void
	*/
	public function update($comment, $request) {
		try {
			if (!empty($request->photo_link))
			{
				$picture = $this->fileService->fotoUpload($request->photo_link, 0, 'img/dnev_comment/');
			}
			DiaryComment::find($comment->id)->update([
				'title'				=> $request->title,
				'description'		=> $request->description,
				'picture'			=> !empty($picture) ? $picture : $comment->picture
			]);
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
			$this->fileService->remove($comment->picture_url);
			DiaryComment::find($comment->id)->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment. '.$e->getMessage());
		}
	}
}