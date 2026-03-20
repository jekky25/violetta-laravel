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
		return Country::select('*')->orderBy('name', 'asc')->get();
	}

	/**
	* get a country by id
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Country::select('*')->whereKey($id)->first();
	}
}