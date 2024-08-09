<?php

namespace App\Repositories;

use App\Interfaces\SmileInterface;
use App\Models\Smile;

class SmileRepository implements SmileInterface {

	/**
	* get smiles
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAll()
	{
		$items = Smile::select('*')
		->orderBy('smile_sort', 'asc')
		->orderBy('smile_id', 'asc')
		->get();
		return $items;
	}
}