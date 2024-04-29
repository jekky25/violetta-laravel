<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnketEvaluation extends Model
{
	use HasFactory;

	protected $table = 'ocenka_anket';
	public $timestamps 	= false;

	protected $fillable = [
		'user_id',
		'user_id_ocenka',
		'ball',
		'time'
	  ];

	public static function getEvaluations($userIdAct, $uderId)
	{
		$items = self::select('*')
		->where('user_id', $userIdAct)
		->where('user_id_ocenka', $uderId)
        ->get();
    	return $items;
	}

	public static function getSum($id)
	{
		$item = self::select(['*'])
		->where('user_id_ocenka', $id)
		->sum('ball','sum_ank');
		return (int)$item;
	}
}
