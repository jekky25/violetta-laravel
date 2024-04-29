<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
	use HasFactory;

	protected $table = 'regions';

	public static function getByCountryId($id)
	{
		$items = self::select('*')
		->where ('country_id', $id)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

	public static function getById($id)
	{
		$item = self::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}

}
