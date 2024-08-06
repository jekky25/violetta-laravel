<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	use HasFactory;
	protected $table = 'city';

	/**
    * get city by cityId
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