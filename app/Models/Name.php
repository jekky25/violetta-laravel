<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Name extends Model
{
	use HasFactory;

	protected $table = 'names';

	public function getPart($id, $sex)
	{
		$items = self::select('*')
		->where('gender', '=', $sex)
		->where('name_id', '=', $id)
		->limit(3)		
		->get();

		if (empty ($items)) return false;

		return $items;
	}


}
