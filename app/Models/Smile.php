<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Smile extends Model
{
	use HasFactory;

	protected $table = 'smiles';

	public function getAll()
	{
		$items = self::select('*')
		->orderBy('smile_sort', 'asc')
		->orderBy('smile_id', 'asc')
		->get();
		return $items;
	}
}
