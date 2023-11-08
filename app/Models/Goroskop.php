<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goroskop extends Model
{
	use HasFactory;

	protected $table = 'goroskop';

	public function getByType($type)
	{
		$items = self::select('*')
		->where('gor_type', $type)
		->orderBy('gor_id', 'asc')
		->get();

		return $items;
	}
}
