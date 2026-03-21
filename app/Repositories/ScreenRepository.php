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
		return Screen::select('*')->orderBy('date', 'desc')->paginate($count);
	}

	/**
	* get a screensaver by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Screen::select('*')->whereKey($id)->firstOrFail();
	}

	/**
	* update a screensaver
	* @param  App\Models\Screen $request
	* @return void
	*/	
	public function update($request) {
		try {
			Screen::whereKey($request->id)->update([
				'zakachka'   => $request->zakachka
			]);
		} catch (\Exception $e) {
			throw new \Exception('Failed to update a screen '.$e->getMessage());
		}
	}
}