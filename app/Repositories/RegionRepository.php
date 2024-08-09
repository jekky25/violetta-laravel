<?php

namespace App\Repositories;

use App\Interfaces\RegionInterface;
use App\Models\Region;

class RegionRepository implements RegionInterface {
	/**
	* get regions by country id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByCountryId($id)
	{
		$items = Region::select('*')
		->where ('country_id', $id)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	/**
	* get region by region id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = Region::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}
}