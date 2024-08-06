<?php

namespace App\Repositories;

use App\Interfaces\CountryInterface;
use App\Models\Country;

class CountryRepository implements CountryInterface {
	/**
	* get all the countries
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAll()
	{
		$items = Country::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	/**
	* get a country by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		$item = Country::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}
}