<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketVisit extends Model
{
	use HasFactory;

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

	public function scopeCreatedFrom($query, int $timeStamp)
	{
	    return $query->where('create_time', '>', $timeStamp);
	}

	public function scopeUserId($query, $userId)
	{
	    return $query->where('user_id', $userId);
	}
}
