<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	use HasFactory;
	protected $table = 'regions';

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
