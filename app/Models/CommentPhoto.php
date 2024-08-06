<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	/**
	* get user
	*/
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id')->with('city');
	}
}