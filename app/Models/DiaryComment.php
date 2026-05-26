<?php

namespace App\Models;

use App\Traits\HasUserId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryComment extends Model
{
	use HasFactory, HasUserId;
	protected $table = 'dnevniki_comments';
	protected $fillable = [
		'diary_id',
		'user_id',
		'title',
		'description',
		'picture',
		'create_time'
	];

	public $timestamps 		= false;
	protected $primaryKey 	= 'id';

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

	public function getUserSexAttribute()
	{
		return !empty($this->user) && isset($this->user->sex) ? $this->user->sex : null;
	}

	public function getNameClassAttribute()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
	}

	public function setTitleAttribute($val)
	{
		$this->attributes['title'] = strip_tags($val, "<b><strong><i>");
	}

	public function setDescriptionAttribute($val)
	{
		$this->attributes['description'] = strip_tags($val, "<b><strong><i>");
	}

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeDiaryId($query, $id)
	{
		return $query->where('diary_id', $id);
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
