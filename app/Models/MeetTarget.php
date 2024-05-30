<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetTarget extends Model
{
	use HasFactory;

	protected $table = 'target_meet';
	
	/**
    * get all types of meeting targets
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
