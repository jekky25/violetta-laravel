<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentPhoto extends Model
{
	use HasFactory;

	protected $table 	= 'comments_fotos';
	public $timestamps 	= false;

	protected $fillable = [
		'foto_id',
		'user_id',
		'time',
		'comments_description',
	  ];

	public function getPaginate($foto_id, $count)
    {
		$items = self::select('*')
		->where ('foto_id', $foto_id)
        ->orderBy('time', 'desc')
		->paginate($count);

		return $items;
	}

	public function user()
	{
    	return $this->belongsTo(User::class, 'user_id', 'user_id')->with('city');
	}
}
