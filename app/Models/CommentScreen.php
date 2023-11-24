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
		
		foreach ($items as &$_item)
		{
			if (!empty($_item->user) )
				$_item->name = $_item->user->user_name;
			$_item->time = date("d-m-Y",$_item->time);
		}

		return $items;
	}

	public function user()
	{
    	return $this->belongsTo(User::class, 'user_id', 'user_id');
	}
}
