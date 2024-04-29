<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Screen extends Model
{
	use HasFactory;

	protected $table 	= 'screensavers';
	public $timestamps 	= false;

	public static function get($count = 0)
	{
		$items = self::select('*')
		->orderBy('date', 'desc')
		->paginate($count);

		return $items;
	}

	public static function getById($id)
	{
		$item = self::select('*')
		->where('id', $id)
		->first();

		return $item;
	}
}
