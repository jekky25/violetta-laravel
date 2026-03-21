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
		return Goroskop::select('*')->type($type)->orderBy('id', 'asc')->get();
	}

	/**
	* get goroskop by goroskopId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Goroskop::select('*')->whereKey($id)->firstOrFail();
	}
}