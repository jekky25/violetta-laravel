<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
	use HasFactory;

	protected $table 	= 'screensavers';
	public $timestamps 	= false;

	/**
    * get screensavers
	* @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function get($count = 0)
	{
		$items = self::select('*')
		->orderBy('date', 'desc')
		->paginate($count);

		return $items;
	}

	/**
    * get a screensaver by id
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
}
