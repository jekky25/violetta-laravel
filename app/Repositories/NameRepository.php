<?php

namespace App\Repositories;

use App\Interfaces\NameInterface;
use App\Models\Name;

class NameRepository implements NameInterface {
	/**
	* get three names by first literal and sex
	* @param  int $id
	* @param  string $sex
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getPart($id, $sex)
	{
		$items = Name::select('*')->gender($sex)->nameId($id)->limit(3)->get();
		return !empty($items) ? $items : null;
	}

	/**
	* get all names by first literal and sex
	* @param  string $sex
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAllbySex($sex, $id)
	{
		$items = Name::select('*')->gender($sex)->nameId($id)->get();
		if (empty ($items)) return null;
		return !empty($items) ? $items : null;
	}

	/**
	* get a name by nameId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getById($id)
	{
		return Name::select('*')->whereKey($id)->firstOrFail();
	}
}