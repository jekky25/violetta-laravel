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
		return DreamBook::select('first_bukva', 'sonnik_id')->groupBy('first_bukva', 'sonnik_id')->get();
	}

	/**
	* get dreambook by $option
	* @param  int $count
	* @param  int $op	
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function get($count = 0, $op = 1)
	{
		return DreamBook::select('*')->dreamBookOptionId($op)->orderBy('name', 'asc')->paginate($count);
	}

	/**
	* get dreambook by dreambookId
	* @param  int $id
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getById($id)
	{
		return DreamBook::select('*')->whereKey($id)->firstOrFail();
	}

	/**
	* get all dreambooks
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAll()
	{
		return DreamBook::select('*')->orderBy('name', 'asc')->get();
	}
}