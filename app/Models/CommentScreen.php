<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentScreen extends Model
{
	use HasFactory;

	protected $table 	= 'comments_screen_servers';
	public $timestamps 	= false;
	protected $fillable = [
		'scr_id',
		'user_id',
		'create_time',
		'name',
		'description',
		'email',
	];

	/**
	 * get Name using user->name
	 * @param  string $id
	 * @return string
	 */
	public function getNameAttribute($val)
	{
		$val = !empty($val) ? $val : 'none';
		return !empty($this->user) ? $this->user->name : $val;
	}

	/**
	 * get Time 
	 * @param  string $string
	 * @return string
	 */
	public function getCreateTimeAttribute($val)
	{
		return date("d-m-Y", $val);
	}

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeScreenSaverId($query, int $id)
	{
		return $query->where('scr_id', $id);
	}


	/**
	 * get user
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
