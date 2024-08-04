<?php

namespace App\Repositories;

use App\Interfaces\ScreenInterface;
use App\Models\Screen;

class ScreenRepository implements ScreenInterface {
	/**
	* get screensavers
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function get($count = 0)
	{
		$items = Screen::select('*')
		->orderBy('date', 'desc')
		->paginate($count);
		return $items;
	}
}