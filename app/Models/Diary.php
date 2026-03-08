<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Diary extends Model
{
	use HasFactory;
	protected $table = 'dnevniki';
	protected $fillable = [
		'user_id',
		'title',
		'create_time',
		'description',
		'picture'
	];

	public $timestamps 		= false;
	protected $primaryKey 	= 'id';

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			self::prepare($model);
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
		$model->title			= strip_tags($model->title, "<b><strong><i>");
		$model->description		= strip_tags($model->description, "<b><strong><i>");
		$model->create_time		= time();
		$user					= Auth::user();
		$model->user_id			= $user->id;
	}

	public function getDiaryImgAttribute()
	{
		$img = $this->getImg();
		return is_file($img) ? $img : '';
	}

	public function getUserSexAttribute()
	{
		return !empty($this->user) && isset($this->user->sex) ? $this->user->sex : null;
	}

	public function getDescriptionBriefAttribute()
	{
		return \Illuminate\Support\Str::limit($this->description, 300, $end = '...');
	}

	public function getCommentsCountAttribute()
	{
		return $this->comments->count();
	}

	public function getAddTimeAttribute()
	{
		return $this->create_time;
	}

	public function getFotoUserIdAttribute()
	{
		return !empty($this->user_photo->id) ? $this->user_photo->id : null;
	}

	public function getNameClassAttribute()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function getPictureUrlAttribute()
	{
		$url =  $this->picture !== "0" ? 'img/dnevnik/' . $this->picture : null;
		return is_file($url) ? $url : null;
	}

	public function getCreateTimeAttribute($val)
	{
		return date("d.m.y H:i", $val);
	}

	public function getTitleAttribute($val)
	{
		$val = stripslashes($val);
		return $val == '' ? 'Тема без названия' : $val;
	}

	public function getDescriptionAttribute($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	/**
	 * get user
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id')->with('city');
	}

	/**
	 * get comments
	 */
	public function comments()
	{
		return $this->hasMany(Comment::class, 'diary_id', 'id');
	}

	/**
	 * get user photo
	 */
	public function user_photo()
	{
		return $this->hasOne(Photo::class, 'user_id', 'user_id')->where('main_picture', 1);
	}
}
