<?php

namespace App\Repositories;

use App\Models\GoroskopType;
use App\Interfaces\GoroskopTypeInterface;

class GoroskopTypeRepository implements GoroskopTypeInterface {
	/**
	* get a type of goroskop by typeId
	* @param  int $type
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getNotByType($type)
	{
		$items = GoroskopType::select('*')
		->where('id', '!=' ,$type)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}
}