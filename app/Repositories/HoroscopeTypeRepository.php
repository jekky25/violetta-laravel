<?php

namespace App\Repositories;

use App\Models\HoroscopeType;
use App\Interfaces\HoroscopeTypeInterface;

class HoroscopeTypeRepository implements HoroscopeTypeInterface {
	/**
	* get a type of horoscope by typeId
	* @param  int $type
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getNotByType($type)
	{
		$items = HoroscopeType::select('*')
		->where('id', '!=' , $type)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}
}