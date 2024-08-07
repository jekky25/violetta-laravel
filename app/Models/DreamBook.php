<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamBook extends Model
{
	use HasFactory;
	protected $table 	= 'sonnik';

	/**
    * get all dreambooks
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();

		return $items;
	}
}
