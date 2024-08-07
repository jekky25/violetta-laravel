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
	public static function getByDiary($count, $diaryId)
	{
		$items = DiaryComment::select('*')
		->where ('comment_dnevnik_id', $diaryId)
		->with('user')
		->orderBy('comment_time', 'desc')
		->paginate($count);
		return $items;
	}
}