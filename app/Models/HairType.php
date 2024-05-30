<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HairType extends Model
{
	use HasFactory;

	protected $table = 'hair_type';

	/**
    * get a type of hair by Id
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
