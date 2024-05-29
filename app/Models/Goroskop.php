<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goroskop extends Model
{
	use HasFactory;

	protected $table = 'goroskop';

	/**
    * get goroskop by goroskop type
    * @param  int $type
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByType($type)
	{
		$items = self::select('*')
		->where('gor_type', $type)
		->orderBy('gor_id', 'asc')
		->get();

		return $items;
	}

	/**
    * get goroskop by goroskopId
    * @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getById($id)
	{
		$item = self::select('*')
		->where('gor_id', $id)
		->first();

		return $item;
	}
}
