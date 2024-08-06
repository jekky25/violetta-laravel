<?php

namespace App\Repositories;

use App\Interfaces\DiaryInterface;
use App\Models\Diary;

class DiaryRepository implements DiaryInterface {
	/**
	* get diaries
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function get($count)
	{
		$items = Diary::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
		->limit ($count)
		->orderBy('dnevniki_time', 'desc')
		->get();
		return $items;
	}

	/**
	* get all diaries
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAll($count)
	{
		$items = Diary::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
		->with('user_photo')
		->orderBy('dnevniki_time', 'desc')
		->paginate($count);
		return $items;
	}
}