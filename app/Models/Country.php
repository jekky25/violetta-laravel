<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\CategoryHome;

class Country extends Model
{
	use HasFactory;

	protected $table = 'country';

	/**
    * get all the countries
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll()
    {

        $items = self::select('*')
		->orderBy('name', 'asc')
		->get();
        return $items;
    }

	/**
    * get a country by id
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
