<?php

namespace App\Models;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SmileRepository;

class Message extends Model
{
	use HasFactory;
	protected $table 		= 'user_messages';
	protected $user, $smiles;
	public $timestamps 		= false;
	protected $primaryKey 	= 'id';

	protected $fillable = [
		'sent_user_id',
		'received_user_id',
		'sent_is_deleted',
		'received_is_deleted',
		'create_time',
		'is_new',
		'description'
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		if (empty($this->user))
			$this->user	= Auth::user();
		$this->smiles = new SmileRepository;
	}

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->sent_user_id		= $model->user->id;
			$model->received_user_id	= request('id');
			$model->sent_is_deleted		= 0;
			$model->received_is_deleted	= 0;
			$model->create_time			= time();
			$model->is_new				= 1;
			$model->description			= str_replace("\'", "''", request('description'));
		});
	}

	public function getUserIdAttribute($val)
	{
		if (!empty($val) or empty($this->user)) return $val;
		return $this->user->id == $this->sent_user_id ? $this->received_user_id : $this->sent_user_id;
	}

	public function getUserMesAttribute($val)
	{
		if (!empty($val) or empty($this->user)) return $val;
		return $this->user->id == $this->sent_user_id ? (new UserRepository())->getJustById($this->received_user_id, ['photo']) : (new UserRepository())->getJustById($this->sent_user_id, ['photo']);
	}

	public function getLastDateAttribute()
	{
		return date("d.m.y H:i", $this->create_time);
	}

	public function getPrivmessTextAttribute($val)
	{
		return $this->smiles->transformSmiles($val);
	}

	public function getPhotoMainAttribute()
	{
		if (count($this->user_mes->photo) > 0) {
			$photo = $this->user_mes->photo[0];
			return $photo->id;
		}
		return null;
	}
}
