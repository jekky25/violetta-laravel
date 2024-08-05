<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	/**
     * get the summ of all evaluations
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Collection 
     */
	public static function getSum($id)
	{
		$item = self::select(['*'])
		->where('user_id_ocenka', $id)
		->sum('ball','sum_ank');
		return (int)$item;
	}
}
