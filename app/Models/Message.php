<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
	use HasFactory;
	protected $table 		= 'user_messages';
	protected $user;
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
	}

	/**
    * get all new messages for $user
    * @param  \Illuminate\Database\Eloquent\Collection $messages
	* @param  User $user
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getNewsByUsers ($messages, $user)
	{
		if (count ($messages) == 0) return $messages;
		$users = [];
		foreach ($messages as $item)
		{
			$users[] = $item->user_id;
		}

		if (!empty($users))
		{
			$items = self::select('*')
			->where('user_poluchil', $user->user_id)
			->whereIn('user_otprav', $users)
			->where('mess_new', 1)
			->get();
		}
		foreach ($messages as &$_message)
		{
			$_message->mess_new = 0;
			foreach ($items as $item)
			{
				if ($_message->user_id == $item->user_otprav)
				{
					$_message->mess_new = $item->mess_new;
					break;
				}
			}
		}
		return $messages;
	}

	/**
    * get all messages for $user by time
	* @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getByTimeByUser($id)
	{
		$items = self::select('*')
		->where('user_otprav', $id)
		->where('time', '>', (time() - 5*60))
		->get();
		return $items;
	}

	public function getUserIdAttribute ($val)
	{
		if (!empty ($val) or empty($this->user)) return $val;
		return $this->user->user_id == $this->user_otprav ? $this->user_poluchil : $this->user_otprav;
	}

	public function getUserMesAttribute ($val)
	{
		if (!empty ($val) or empty($this->user)) return $val;
		return $this->user->user_id == $this->user_otprav ? User::getJustById($this->user_poluchil, ['photo']) : User::getJustById($this->user_otprav, ['photo']);
	}

	public function getLastDateAttribute ($val)
	{
		return date("d.m.y H:i",$this->time);
	}

	public function getPhotoMainAttribute ($val)
	{
		if (count ($this->user_mes->photo) > 0)
		{
			$photo = $this->user_mes->photo[0];
			return $photo->fotos_id;
		}
		return null;
	}
}