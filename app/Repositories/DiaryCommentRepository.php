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
		$items = DiaryComment::select('*')
		->where ('comment_dnevnik_id', $diaryId)
		->with('user')
		->orderBy('comment_time', 'desc')
		->paginate($count);
		return $items;
	}

	/**
	* get comments by commentId and userId
	* @param  int $id
	* @param  int $userId
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByUserAndId($id, $userId)
	{
		$item = DiaryComment::select('*')
		->where('comment_id', $id)
		->where('comment_dnevnik_user_id', $userId)
		->firstOrFail();
		return $item;
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
			$request['comment_picture']	= !empty ($picture) ? $picture : "0";
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
			DiaryComment::find($comment->comment_id)->update([
				'comment_title'		=> $request->title,
				'comment_text'		=> $request->description,
				'comment_picture'	=> !empty ($picture) ? $picture : $comment->comment_picture
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
			DiaryComment::find($comment->comment_id)->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary Comment. '.$e->getMessage());
		}
	}
}