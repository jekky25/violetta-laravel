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
}
