<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DiaryComment extends Model
{
	use HasFactory;

	protected $table = 'dnevniki_comments';

	protected $fillable = [
		'comment_id',
		'comment_dnevnik_id',
		'comment_dnevnik_user_id',
		'comment_title',
		'comment_text',
		'comment_picture',
		'comment_time'
    ];

	public $timestamps 		= false;
	protected $primaryKey 	= 'comment_id';

	/**
    * get comments by diaryId
    * @param  int $count
	* @param  int $diaryId
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByDiary($count, $diaryId)
    {
		$items = self::select('*')
		->where ('comment_dnevnik_id', $diaryId)
		->with('user')
        ->orderBy('comment_time', 'desc')
        ->paginate($count);

		return $items;
	}

	/**
    * get comments by commentId and userId
    * @param  int $id
	* @param  int $userId
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByUserAndId($id, $userId)
	{
		$item = self::select('*')
		->where('comment_id', $id)
		->where('comment_dnevnik_user_id', $userId)
		->first();

		return $item;
	}

	public function getAddTimeAttribute ($val)
	{
		return is_numeric($this->comment_time) ? date("d.m.y H:i", $this->comment_time) : $this->comment_time;
	}

	public function getPictureUrlAttribute ($val)
	{	
		return $this->comment_picture !== "0" ? 'img/dnev_comment/' . $this->comment_picture : null;
	}

	public function getCommentTimeAttribute ($val)
	{
		return date("d.m.y H:i", $val);
	}

	/**
    * get user diary
    */
	public function diary()
    {
        return $this->belongsTo(Diary::class, 'dnevniki_id', 'comment_dnevnik_id');
    }

	/**
    * get user
    */
	public function user()
    {
        return $this->belongsTo(User::class, 'comment_dnevnik_user_id', 'user_id');
    }
}
