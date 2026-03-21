<?php
namespace App\Models;

use App\Traits\HasUserId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketEvaluation extends Model
{
	use HasFactory, HasUserId;

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

	public function scopeEvaluationUserId($query, $userId)
	{
	    return $query->where('user_id_ocenka', $userId);
	}
}
