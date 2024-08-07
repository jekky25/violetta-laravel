<?php

namespace App\Repositories;

use App\Interfaces\DreamBookInterface;
use App\Models\DreamBook;

class DreamBookRepository implements DreamBookInterface {

	/**
	* get liters
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getLiter()
	{
		$items = DreamBook::select('first_bukva', 'sonnik_id')
		->groupBy('first_bukva', 'sonnik_id')
		->get();
		return $items;
	}
}