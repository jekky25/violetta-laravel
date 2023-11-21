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

	public function getAllbySex($sex, $id)
	{
		$items = self::select('*')
		->where('gender', '=', $sex)
		->where('name_id', '=', $id)
		->get();

		if (empty ($items)) return null;
		return $items;
	}

	public function getById($id)
	{
		$item = self::select('*')
		->where('id', $id)
		->first();

		return $item;
	}

}
