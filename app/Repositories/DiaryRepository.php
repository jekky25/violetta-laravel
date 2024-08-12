<?php

namespace App\Repositories;

use App\Interfaces\DiaryInterface;
use App\Models\Diary;
use App\Services\LengthPager;

class DiaryRepository implements DiaryInterface {
	/**
	* get diaries
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function get($count)
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
	public function getAll($count)
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

	/**
	* get diaries by userId
	* @param  int $count
	* @param  int $userId
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByUser($count, $userId)
    {
		$items = Diary::select('*')
		->where ('dnevniki_user_id', $userId)
		->with('user')
		->with('comments')
		->with('user_photo')
		->orderBy('dnevniki_time', 'desc')
		->paginate($count);
		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		return $items;
	}

	/**
	* get diary by diaryId and userId
	* @param  int $id
	* @param  int $userId
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByUserAndId($id, $userId)
	{
		$item = Diary::select('*')
		->where('dnevniki_id', $id)
		->where('dnevniki_user_id', $userId)
		->with('comments')
		->first();
		return $item;
	}

	/**
	* get diary by diaryId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = Diary::select('*')
		->where ('dnevniki_id', $id)
		->with('user')
		->first();
		return $item;
	}

	/**
	* create a diary
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			Diary::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary '.$e->getMessage());
		}
	}
}