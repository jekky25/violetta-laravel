<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Diary extends Model
{
	use HasFactory;

	protected $table = 'dnevniki';

	protected $fillable = [
        'dnevniki_user_id',
		'dnevniki_title',
		'dnevniki_time',
		'dnevniki_text',
		'dnevniki_picture'
    ];

	public $timestamps 		= false;
	protected $primaryKey 	= 'dnevniki_id';

	/**
    * get diaries
    * @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function get($count)
    {
		$items = self::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
		->limit ($count)
        ->orderBy('dnevniki_time', 'desc')
        ->get();

		return $items;
	}

	/**
    * get all diaries
    * @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll($count)
    {
		$items = self::select('*')
		->whereHas('user', function ($query) {
			$query->where('user_active', 1);
		})
		->with('user')
		->with('comments')
		->with('user_photo')
        ->orderBy('dnevniki_time', 'desc')
        ->paginate($count);

		return $items;
	}

	/**
    * get diaries by userId
    * @param  int $count
	* @param  int $userId
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByUser($count, $userId)
    {
		$items = self::select('*')
		->where ('dnevniki_user_id', $userId)
		->with('user')
		->with('comments')
		->with('user_photo')
        ->orderBy('dnevniki_time', 'desc')
        ->paginate($count);

		return $items;
	}

	/**
    * get diary by diaryId and userId
    * @param  int $id
	* @param  int $userId
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByUserAndId($id, $userId)
	{
		$item = self::select('*')
		->where('dnevniki_id', $id)
		->where('dnevniki_user_id', $userId)
		->with('comments')
		->first();

		return $item;
	}

	/**
    * get diary by diaryId
    * @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getById($id)
	{
		$item = self::select('*')
		->where ('dnevniki_id', $id)
		->with('user')
		->first();
		return $item;
	}

	/**
    * get diary image link
    * @return string
    */
	public function getImg ()
	{
		return $this->dnevniki_picture !== "0" ? './img/dnevnik/' . $this->dnevniki_picture . '.jpg' : '';
	}


	public function getDnevnikFotoAttribute ()
	{
		$img = $this-> getImg();
		return is_file($img) ? $this->dnevniki_id : null;
	}

	public function getDiaryImgAttribute ()
	{
		$img = $this-> getImg();
		return is_file($img) ? $img : '';
	}
	
	public function getUserSexAttribute ()
	{
		return !empty($this->user) && isset($this->user->user_sex) ? $this->user->user_sex : null;
	}

	public function getAddTimeAttribute ()
	{
		return $this->dnevniki_time;
	}

	public function getFotoUserIdAttribute ()
	{
		return !empty($this->user_photo->fotos_id) ? $this->user_photo->fotos_id : null;
	}

	public function getNameClassAttribute ()
	{
		return  $this->user_sex == MEN ? 'name_man' : 'name_woman';
		
	}
	
	public function getDnevnikiPictureUrlAttribute ($val)
	{	
		return $this->dnevniki_picture !== "0" ? 'img/dnevnik/' . $this->dnevniki_picture : null;
	}

	public function getDnevnikiTimeAttribute ($val)
	{
		return date("d.m.y H:i", $val);
	}

	public function getDnevnikiTitleAttribute ($val)
	{
		$val = stripslashes($val);
		return $val == '' ? 'Тема без названия' : $val;
	}

	public function getDnevnikiTextAttribute ($val)
	{
		$val = stripslashes($val);
		return str_replace("\n", "\n<br />\n", $val);
	}

	/**
    * get user
    */
	public function user()
    {
        return $this->belongsTo(User::class, 'dnevniki_user_id', 'user_id')->with('city');
    }

	/**
    * get comments
    */
	public function comments ()
	{
		return $this->hasMany(Comment::class, 'comment_dnevnik_id', 'dnevniki_id');
	}

	/**
    * get user photo
    */
	public function user_photo ()
	{
		return $this->hasOne(Photo::class, 'user_id', 'dnevniki_user_id')->where('fotos_portret', 1);

	}
}
