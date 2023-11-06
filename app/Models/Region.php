<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
	use HasFactory;

	protected $table = 'regions';

	public function getByCountryId($id)
	{
		$items = self::select('*')
		->where ('country_id', $id)
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

}
