<?php

namespace App\Repositories;

use App\Interfaces\GoroskopInterface;
use App\Models\Goroskop;

class GoroskopRepository implements GoroskopInterface {
	/**
	* get goroskop by goroskop type
	* @param  int $type
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByType($type)
	{
		$items = Goroskop::select('*')
			->where('gor_type', $type)
			->orderBy('gor_id', 'asc')
			->get();
		return $items;
	}

	/**
	* get goroskop by goroskopId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = Goroskop::select('*')
		->where('gor_id', $id)
		->first();

		return $item;
	}
}