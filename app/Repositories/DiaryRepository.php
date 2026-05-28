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
		return Diary::select('*')->activeUser()->with(['user', 'comments'])->limit($count)->orderBy('create_time', 'desc')->get();
	}

	/**
	 * get all diaries
	 * @param  int $count
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll($count)
	{
		return Diary::select('*')->activeUser()->with(['user', 'comments', 'user_photo'])->orderBy('create_time', 'desc')->paginate($count);
	}

	/**
	 * get diaries by userId
	 * @param  int $count
	 * @param  int $userId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByUser($count, $userId)
	{
		$items = Diary::select('*')->userId($userId)->with(['user', 'comments', 'user_photo'])->orderBy('create_time', 'desc')->paginate($count);
		return LengthPager::makeLengthAware($items, $items->total(), $count);
	}

	/**
	 * get diary by diaryId and userId
	 * @param  int $id
	 * @param  int $userId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByUserAndId($id, $userId)
	{
		return Diary::select('*')->whereKey($id)->userId($userId)->with('comments')->firstOrFail();
	}

	/**
	 * get diary by diaryId
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function getById($id)
	{
		return Diary::select('*')->whereKey($id)->with('user')->firstOrFail();
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
	 */
	public function update(int $diaryId, array $data): void
	{
		try {
			Diary::find($diaryId)->update($data);
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
			$diary->comments()->delete();
			$diary->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Diary . ' . $e->getMessage());
		}
	}
}
