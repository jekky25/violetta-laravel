<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoroskopType extends Model
{
	use HasFactory;

	protected $table = 'goroskop_type';

	public static function getNotByType($type)
	{
		$items = self::select('*')
		->where('gor_type_id', '!=' ,$type)
		->orderBy('gor_type_name', 'asc')
		->get();

		return $items;
	}
}
