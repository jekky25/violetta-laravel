<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommentPhoto;

class Photo extends Model
{
	use HasFactory;
	protected $table 		= 'fotos';
	protected $primaryKey 	= 'fotos_id';
	public $timestamps 		= false;

	protected $fillable = [
		'fotos_portret',
		'user_id'
	];

	/**
    * get the first pucture by userId
	* @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getFirstByUserId($id)
	{
		$item = self::select('*')
		->where ('user_id', $id)
		->first();
		return $item;
	}

	/**
    * get all puctures by userId
	* @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAllByUserId($id)
	{
		$item = self::select('*')
		->where ('user_id', $id)
		->get();
		return $item;
	}

	/**
    * get comments
    */
	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'fotos_id')->with('user');
	}
}
