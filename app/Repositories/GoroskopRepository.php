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
}