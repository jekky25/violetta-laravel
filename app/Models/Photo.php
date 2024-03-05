<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\CategoryHome;
use App\Models\CommentPhoto;

class Photo extends Model
{
	use HasFactory;

	protected $table 		= 'fotos';
	protected $primaryKey 	= 'fotos_id';
	public $timestamps 		= false;

	public function getCountPhotos ()
	{
		$count = self::select('fotos_id')->count();
		return $count > 0 ? $count : 0;
	}

	public function getById($id)
	{
		$item = self::select('*')
		->where ('fotos_id', $id)
		->first();
		return $item;
	}

	public function getFirstByUserId($id)
	{
		$item = self::select('*')
		->where ('user_id', $id)
		->first();
		return $item;
	}

	public function getAllByUserId($id)
	{
		$item = self::select('*')
		->where ('user_id', $id)
		->get();
		return $item;
	}

	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'fotos_id')->with('user');
	}
}
