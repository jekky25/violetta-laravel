<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vars extends Model
{
	use HasFactory;

	protected $table = 'vars';

	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();
		$vars = [];
		foreach ($items as $item)
		{
			$vars [$item->name] = $item->value;
		}
		return $vars;
	}
}
