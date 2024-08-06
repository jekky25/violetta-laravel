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
}