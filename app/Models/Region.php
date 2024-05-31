<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
	use HasFactory;

	protected $table = 'regions';

	/**
    * get regions by country id
	* @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByCountryId($id)
	{
		$items = self::select('*')
		->where ('country_id', $id)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	/**
    * get region by region id
	* @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getById($id)
	{
		$item = self::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}

}
