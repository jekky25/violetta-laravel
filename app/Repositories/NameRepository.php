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
		$items = Name::select('*')
		->where('gender', '=', $sex)
		->where('name_id', '=', $id)
		->limit(3)		
		->get();

		if (empty ($items)) return null;
		return $items;
	}
}