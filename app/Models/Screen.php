<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Screen extends Model
{
	use HasFactory;

	protected $table = 'screensavers';

	public function get($count = 0)
	{
		$items = self::select('*')
		->orderBy('date', 'desc')
		->paginate($count);

		return $items;
	}
}
