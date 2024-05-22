<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentScreen extends Model
{
	use HasFactory;

	protected $table 	= 'comments_screen_servers';
	public $timestamps 	= false;
	protected $fillable = [
		'scr_id',
		'user_id',
		'time',
		'name',
		'description',
		'email',
	  ];

	/**
    * get screensaver comments over screensaver id
    * @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByScrId($id)
	{
		$items = self::select('*')
		->where('scr_id', $id)
		->with ('user')
		->orderBy('time', 'desc')
		->get();
		return $items;
	}

	/**
    * get Name using user->user_name
    * @param  string $id
    * @return string
    */
    public function getNameAttribute ($val)
    {
		$val = !empty ($val) ? $val : 'none';
		return !empty($this->user) ? $this->user->user_name : $val;
    }

	/**
    * get Time 
    * @param  string $string
    * @return string
    */
	public function getTimeAttribute ($val)
    {
		return date("d-m-Y",$val);
    }

	/**
    * get user
    */
	public function user()
	{
    	return $this->belongsTo(User::class, 'user_id', 'user_id');
	}
}
