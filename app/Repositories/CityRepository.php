<?php

namespace App\Repositories;

use App\Interfaces\CityInterface;
use App\Models\City;

class CityRepository implements CityInterface {
	/**
	* get cities of the region by regionId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByRegionId($id)
	{
		$items = City::select('*')
		->where ('regions_id', $id)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	/**
	* get city by cityId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = City::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}
}