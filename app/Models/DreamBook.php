<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DreamBook extends Model
{
	use HasFactory;

	protected $table 	= 'sonnik';

	/**
    * get liters
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getLiter()
	{
		$items = self::select('first_bukva', 'sonnik_id')
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
	public static function get($count = 0, $op = 1)
	{
		$items = self::select('*')
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
	public static function getById($id)
	{
		$item = self::select('*')
		->where('id', $id)
		->first();

		return $item;
	}

	/**
    * get all dreambooks
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();

		return $items;
	}
}
