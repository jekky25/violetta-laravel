<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Interest extends Model
{
	use HasFactory;

	protected $table = 'interest';
	
	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

}
