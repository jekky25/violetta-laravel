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
	* get comments
	*/
	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'fotos_id')->with('user');
	}
}