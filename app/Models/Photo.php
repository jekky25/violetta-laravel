<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommentPhoto;
use App\Traits\HasUserId;

class Photo extends Model
{
	use HasFactory, HasUserId;
	protected $table 		= 'fotos';
	protected $primaryKey 	= 'id';
	public $timestamps 		= false;

	protected $fillable = [
		'main_picture',
		'user_id'
	];

	/**
	* get comments
	*/
	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'id')->with('user');
	}
}