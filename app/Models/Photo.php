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
		'user_id',
		'path',
		'public_path'
	];

	public function getUrlAttribute()
	{
		$photoName = !empty($this->path) ? $this->path : $this->id . '.jpg';
		return asset(config('photos.folder') . $photoName);
	}

	public function getPublicPathAttribute()
	{
		$photoName = !empty($this->path) ? $this->path : $this->id . '.jpg';
		return public_path(config('photos.folder') . $photoName);
	}

	/**
	* get comments
	*/
	public function comment()
	{
		return $this->hasMany(CommentPhoto::class, 'foto_id', 'id')->with('user');
	}
}