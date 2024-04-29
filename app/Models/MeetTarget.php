<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetTarget extends Model
{
	use HasFactory;

	protected $table = 'target_meet';
	
	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

}
