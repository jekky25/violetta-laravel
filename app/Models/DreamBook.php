<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamBook extends Model
{
	use HasFactory;
	protected $table 	= 'sonnik';

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
