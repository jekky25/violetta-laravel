<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\CategoryHome;

class City extends Model
{
	use HasFactory;

	protected $table = 'city';

	public static function getByRegionId($id)
	{
		$items = self::select('*')
		->where ('regions_id', $id)
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
