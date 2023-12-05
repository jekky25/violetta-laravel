<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SpeakLang extends Model
{
	use HasFactory;

	protected $table = 'speak_lang';
	
	public function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

}
