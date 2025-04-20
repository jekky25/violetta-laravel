<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
		return old('title') ? old('title') : stripslashes($val);
	}

	public function getDescriptionAttribute($val)
	{
		return old('description') ? old('description') : $val;
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
