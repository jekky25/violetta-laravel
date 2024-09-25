<?php
namespace App\Models;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SmileRepository;
use App\Helpers\Helper;

class Message extends Model
{
	use HasFactory;
	protected $table 		= 'user_messages';
	protected $user, $smiles;
	public $timestamps 		= false;
	protected $primaryKey 	= 'message_id';

	protected $fillable = [
		'user_otprav',
		'user_poluchil',
		'user_otprav_del',
		'user_poluchil_del',
		'time',
		'mess_new',
		'privmess_text'
	];

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		if (empty($this->user)) 
		$this->user	= Auth::user();
		$this->smiles = (new SmileRepository)->getAll();
	}

	public function getUserIdAttribute ($val)
	{
		if (!empty ($val) or empty($this->user)) return $val;
		return $this->user->user_id == $this->user_otprav ? $this->user_poluchil : $this->user_otprav;
	}

	public function getUserMesAttribute ($val)
	{
		if (!empty ($val) or empty($this->user)) return $val;
		return $this->user->user_id == $this->user_otprav ? (new UserRepository())->getJustById($this->user_poluchil, ['photo']) : (new UserRepository())->getJustById($this->user_otprav, ['photo']);
	}

	public function getLastDateAttribute ()
	{
		return date("d.m.y H:i",$this->time);
	}

	public function getPrivmessTextAttribute ($val)
	{
		return Helper::transformSmiles ($val, $this->smiles);
	}

	public function getPhotoMainAttribute ()
	{
		if (count ($this->user_mes->photo) > 0)
		{
			$photo = $this->user_mes->photo[0];
			return $photo->fotos_id;
		}
		return null;
	}
}