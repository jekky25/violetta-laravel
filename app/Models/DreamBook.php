<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DreamBook extends Model
{
	use HasFactory;

	protected $table 	= 'sonnik';

	public function getLiter()
	{
		$items = self::select('first_bukva', 'sonnik_id')
		->groupBy('first_bukva', 'sonnik_id')
		->get();
		return $items;
	}

	public function get($count = 0, $op = 1)
	{
		$items = self::select('*')
		->where('sonnik_id', $op)
		->orderBy('name', 'asc')
		->paginate($count);

		return $items;
	}

	public function getById($id)
	{
		$item = self::select('*')
		->where('id', $id)
		->first();

		return $item;
	}

	public function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();

		return $items;
	}
}
