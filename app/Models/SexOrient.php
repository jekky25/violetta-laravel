<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SexOrient extends Model
{
	use HasFactory;

	protected $table = 'sex_orient';

	public static function getById($id)
	{
		$item = self::select('*')
		->where ('id', $id)
		->first();
		return $item;
	}
	
}
