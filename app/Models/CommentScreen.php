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

	public function getByScrId($id)
	{
		$items = self::select('*')
		->where('scr_id', $id)
		->with ('user')
		->orderBy('time', 'desc')
		->get();
		return $items;
	}

	public function user()
	{
    	return $this->belongsTo(User::class, 'user_id', 'user_id');
	}
	
    public function getNameAttribute ($val)
    {
		$val = !empty ($val) ? $val : 'none';
		return !empty($this->user) ? $this->user->user_name : $val;
    }

	public function getTimeAttribute ($val)
    {
		return date("d-m-Y",$val);
    }
}
