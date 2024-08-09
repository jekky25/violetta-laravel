<?php

namespace App\Repositories;

Use App\Interfaces\VarsInterface;
use App\Models\Vars;

class VarsRepository implements VarsInterface {

	/**
	* get all vars
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public static function getAll()
	{
		$items = Vars::select('*')
		->orderBy('name', 'asc')
		->get();
		$vars = [];
		foreach ($items as $item)
		{
			$vars [$item->name] = $item->value;
		}
		return $vars;
	}
}