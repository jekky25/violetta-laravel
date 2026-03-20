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
		return City::select('*')->regionId($id)->orderBy('name', 'asc')->get();
	}

	/**
	* get city by cityId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return City::select('*')->whereKey($id)->first();
	}
}