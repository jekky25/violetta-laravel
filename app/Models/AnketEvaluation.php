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

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeUserId($query, $userId)
	{
	    return $query->where('user_id', $userId);
	}

	public function scopeEvaluationUserId($query, $userId)
	{
	    return $query->where('user_id_ocenka', $userId);
	}
}
