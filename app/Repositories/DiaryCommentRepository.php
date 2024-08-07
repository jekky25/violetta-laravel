<?php

namespace App\Repositories;

use App\Interfaces\DiaryCommentInterface;
use App\Models\DiaryComment;

class DiaryCommentRepository implements DiaryCommentInterface {
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
		->first();
		return $item;
	}
}