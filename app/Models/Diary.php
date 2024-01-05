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

	public function get($count)
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

	public function getAll($count)
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

	public function getByUser($count, $userId)
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

	public function getByUserAndId($id, $userId)
	{
		$item = self::select('*')
		->where('dnevniki_id', $id)
		->where('dnevniki_user_id', $userId)
		->with('comments')
		->first();

		return $item;
	}

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

	public function user()
    {
        return $this->belongsTo(User::class, 'dnevniki_user_id', 'user_id');
    }

	public function comments ()
	{
		return $this->hasMany(Comment::class, 'comment_dnevnik_id', 'dnevniki_id');
	}

	public function user_photo ()
	{
		return $this->hasOne(Photo::class, 'user_id', 'dnevniki_user_id')->where('fotos_portret', 1);

	}
}
