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

	/**
	* get dreambook by $option
	* @param  int $count
	* @param  int $op	
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function get($count = 0, $op = 1)
	{
		$items = DreamBook::select('*')
		->where('sonnik_id', $op)
		->orderBy('name', 'asc')
		->paginate($count);
		return $items;
	}

	/**
	* get dreambook by dreambookId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getById($id)
	{
		$item = DreamBook::select('*')
		->where('id', $id)
		->firstOrFail();

		return $item;
	}

	/**
	* get all dreambooks
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAll()
	{
		$items = DreamBook::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}
}