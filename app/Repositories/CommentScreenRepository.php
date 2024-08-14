<?php

namespace App\Repositories;

use App\Interfaces\CommentScreenInterface;
use App\Models\CommentScreen;

class CommentScreenRepository implements CommentScreenInterface {
	/**
	* get screensaver comments over screensaver id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getByScrId($id)
	{
		$items = CommentScreen::select('*')
		->where('scr_id', $id)
		->with ('user')
		->orderBy('time', 'desc')
		->get();
		return $items;
	}

	/**
	* create a comment for the screensaver
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			CommentScreen::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a comment '.$e->getMessage());
		}
	}
}