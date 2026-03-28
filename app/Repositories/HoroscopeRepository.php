<?php

namespace App\Repositories;

use App\Interfaces\HoroscopeInterface;
use App\Models\Horoscope;

class HoroscopeRepository implements HoroscopeInterface {
	/**
	* get horoscope by horoscope type
	* @param  int $type
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByType(int $type)
	{
		return Horoscope::select('*')->type($type)->orderBy('id', 'asc')->get();
	}

	/**
	* get horoscope by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Horoscope::select('*')->whereKey($id)->firstOrFail();
	}
}