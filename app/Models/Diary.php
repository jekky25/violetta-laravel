<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	/**
	* get diary image link
	* @return string
	*/
	public function getImg()
	{
		return $this->picture !== "0" ? './img/dnevnik/' . $this->picture . '.jpg' : '';
	}

	public function getDnevnikFotoAttribute()
	{
		$img = $this-> getImg();
		return is_file($img) ? $this->id : null;
	}

	public function getDiaryImgAttribute()
	{
		$img = $this-> getImg();
		return is_file($img) ? $img : '';
	}
	
	public function getUserSexAttribute()
	{
		return !empty($this->user) && isset($this->user->user_sex) ? $this->user->user_sex : null;
	}

	public function getAddTimeAttribute()
	{
		return $this->create_time;
	}

	public function getFotoUserIdAttribute()
	{
		return !empty($this->user_photo->fotos_id) ? $this->user_photo->fotos_id : null;
	}

	public function getNameClassAttribute()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
		
	}
	
	public function getPictureUrlAttribute()
	{	
		return $this->picture !== "0" ? 'img/dnevnik/' . $this->picture : null;
	}

	public function getDnevnikiTimeAttribute($val)
	{
		return date("d.m.y H:i", $val);
	}

	public function getDnevnikiTitleAttribute($val)
	{
		$val = stripslashes($val);
		return $val == '' ? 'Тема без названия' : $val;
	}

	public function getDnevnikiTextAttribute($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	/**
	* get user
	*/
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id')->with('city');
	}

	/**
	* get comments
	*/
	public function comments()
	{
		return $this->hasMany(Comment::class, 'comment_dnevnik_id', 'id');
	}

	/**
	* get user photo
	*/
	public function user_photo()
	{
		return $this->hasOne(Photo::class, 'user_id', 'user_id')->where('fotos_portret', 1);
	}
}