<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SpeakLang extends Model
{
	use HasFactory;

	protected $table = 'speak_lang';

	/**
    * get all types of speaking language
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll()
	{
		$items = self::select('*')
		->orderBy('name', 'asc')
		->get();
		return $items;
	}

}
