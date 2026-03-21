<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommentPhoto;

class Photo extends Model
{
	use HasFactory;
	protected $table 		= 'fotos';
	protected $primaryKey 	= 'id';
	public $timestamps 		= false;

	protected $fillable = [
		'main_picture',
		'user_id'
	];

	/***********************************
	 * SCOPES
	***********************************/

	public function scopeUserId($query, $userId)
	{
		return $query->where('user_id', $userId);
	}

	/**
	* get comments
	*/
	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'id')->with('user');
	}
}