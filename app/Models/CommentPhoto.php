<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommentPhoto extends Model
{
	use HasFactory;
	protected $table 	= 'comments_fotos';
	public $timestamps 	= false;

	protected $fillable = [
		'foto_id',
		'user_id',
		'time',
		'description',
	];

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->time			= time();
			$model->description		= str_replace("\'", "''", $model->description);
			$user 					= Auth::user();
			$model->user_id			= $user->user_id;
		});
	}

	public function getAddTimeAttribute()
	{
		return date("d.m.y H:i", $this->time);
	}

	public function setCommentsDescriptionAttribute($val)
	{
		$this->attributes['description'] = strip_tags($val);
	}

	public function getCommentsDescriptionAttribute($val)
	{
		return str_replace("\n", "\n<br />\n", $val);
	}

	public function getUserPhotoIdAttribute()
	{
		return !empty($this->user->photo[0]) ? $this->user->photo[0]->id : 0;
	}

	/**
	 * get user
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id')->with('city');
	}
}
