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
		->where('gor_type_id', '!=' ,$type)
		->orderBy('gor_type_name', 'asc')
		->get();
		return $items;
	}
}