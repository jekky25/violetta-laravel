<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SmileRepository;

class Message extends Model
{
	use HasFactory;
	protected $table 		= 'user_messages';
	protected $smiles;
	protected static $authUser = null;
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
		self::$authUser = self::AuthUser();
		$this->smiles = new SmileRepository;
	}

	protected static function AuthUser()
	{
		if (self::$authUser === null) {
			self::$authUser = Auth::user();
		}
		return self::$authUser;
	}

	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->sent_user_id		= self::AuthUser()->id;
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

	public function getNameClassAttribute()
	{
		return $this->sent_user_id == self::AuthUser()->id ? 'name_man' : 'name_woman';
	}

	public function getAddTimeAttribute()
	{
		return $this->create_time;
	}

	public function getCreateTimeAttribute($val)
	{
		return date("d.m.y H:i", $val);
	}

	public function getPrivmessTextAttribute($val)
	{
		return $this->smiles->transformSmiles($val);
	}

	public function getPhotoMainAttribute()
	{
		if (count($this->user->photo) > 0) {
			$photo = $this->user->photo[0];
			return $photo->id;
		}
		return null;
	}
	
	public function user(): HasOne
	{
		return $this->hasOne(User::class, 'id', 'sent_user_id');
	}
}
