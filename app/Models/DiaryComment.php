<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	public function getTitleAttribute ()
	{
		return old('title') ? old('title') : stripslashes ($this->comment_title);
	}

	public function getTextAttribute ()
	{
		return old('description') ? old('description') : $this->comment_text;
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