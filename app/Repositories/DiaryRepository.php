<?php

namespace App\Repositories;

use App\Interfaces\DiaryInterface;
use App\Models\Diary;
use App\Services\LengthPager;
use App\Services\FileService;

class DiaryRepository implements DiaryInterface
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected FileService $fileService
	) {}

	/**
	 * get diaries
	 * @param  int $count
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function get($count)
	{
		$items = Diary::select('*')
			->whereHas('user', function ($query) {
				$query->where('active', 1);
			})
			->with('user')
			->with('comments')
			->limit($count)
			->orderBy('create_time', 'desc')
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
				$query->where('active', 1);
			})
			->with('user')
			->with('comments')
			->with('user_photo')
			->orderBy('create_time', 'desc')
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
			->where('user_id', $userId)
			->with('user')
			->with('comments')
			->with('user_photo')
			->orderBy('create_time', 'desc')
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
			->where('id', $id)
			->where('user_id', $userId)
			->with('comments')
			->firstOrFail();
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
			->where('id', $id)
			->with('user')
			->firstOrFail();
		return $item;
	}

	/**
	 * create a diary
	 * @param  array $request
	 * @return void
	 */
	public function create($request)
	{
		try {
			Diary::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary ' . $e->getMessage());
		}
	}

	/**
	 * store a diary
	 * @param  array $request
	 * @return void
	 */
	public function store($request)
	{
		try {
			if (!empty($request['photo_link'])) {
				$picture = $this->fileService->fotoUpload($request['photo_link'], 0, 'img/dnevnik/');
			}
			$request['picture'] = !empty($picture) ? $picture : "0";
			Diary::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a Diary ' . $e->getMessage());
		}
	}

	/**
	 * update a diary
	 * @param  Diary $comment
	 * @param  DiaryRequest $request
	 * @return void
	 */
	public function update($diary, $request)
	{
		try {
			if (!empty($request->photo_link)) {
				$picture = $this->fileService->fotoUpload($request->photo_link, 0, 'img/dnevnik/');
			}
			Diary::find($diary->id)->update([
				'title'			=> $request->title,
				'description'	=> $request->description,
				'picture'		=> !empty($picture) ? $picture : $diary->picture
			]);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update Diary. ' . $e->getMessage());
		}
	}

	/**
	 * delete a diary
	 * @param  Diary $diary
	 * @return void
	 */
	public function delete($diary)
	{
		try {
			$this->fileService->remove($diary->picture_url);
			$diary->comments()->delete();
			$diary->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary . ' . $e->getMessage());
		}
	}
}
