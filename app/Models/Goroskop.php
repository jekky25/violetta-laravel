<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goroskop extends Model
{
	use HasFactory;

	protected $table = 'goroskop';

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
