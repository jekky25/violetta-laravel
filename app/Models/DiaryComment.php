<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DiaryComment extends Model
{
	use HasFactory;
	protected $table = 'dnevniki_comments';
	protected $fillable = [
		'id',
		'diary_id',
		'user_id',
		'title',
		'description',
		'picture',
		'create_time'
	];

	public $timestamps 		= false;
	protected $primaryKey 	= 'id';

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			self::prepare($model);
			$model->diary_id	= request('id');
		});
		self::updating(function ($model) {
			self::prepare($model);
		});
	}

	/**
	 * Preparating params before create or update this model
	 *
	 * @param  DiaryComment $model
	 * @return void
	 */
	protected static function prepare($model)
	{
		$model->create_time		= time();
		$model->title			= strip_tags($model->title, "<b><strong><i>");
		$model->description		= strip_tags($model->description, "<b><strong><i>");
		$user					= Auth::user();
		$model->user_id			= $user->id;
	}

	public function getAddTimeAttribute()
	{
		return is_numeric($this->create_time) ? date("d.m.y H:i", $this->create_time) : $this->create_time;
	}

	public function getPictureUrlAttribute()
	{
		return $this->picture !== "0" ? 'img/dnev_comment/' . $this->picture : null;
	}

	public function getCreateTimeAttribute($val)
	{
		return date("d.m.y H:i", $val);
	}

	public function getTitleAttribute($val)
	{
		return stripslashes($val);
	}

	/**
	 * get user diary
	 */
	public function diary()
	{
		return $this->belongsTo(Diary::class, 'id', 'diary_id');
	}

	/**
	 * get user
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
