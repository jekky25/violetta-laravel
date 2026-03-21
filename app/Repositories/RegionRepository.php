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
		return Region::select('*')->countryId($id)->orderBy('name', 'asc')->get();
	}

	/**
	* get region by region id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Region::select('*')->whereKey($id)->first();
	}
}