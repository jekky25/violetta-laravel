<?php

namespace App\Models;

use App\Traits\HasUserId;
use App\Traits\HasCreatedFrom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketVisit extends Model
{
	use HasFactory, HasUserId, HasCreatedFrom;

	public $timestamps 	= false;
	protected $table = 'anket_visit';
	protected $fillable = [
		'user_id',
		'user_id_prosm',
		'create_time'
	];

	/**
	 * get user
	 */
	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id_prosm')
			->with('city')
			->with('photo');
	}

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeVisitedUserId($query, int $userId)
	{
		return $query->where('user_id_prosm', $userId);
	}
}
