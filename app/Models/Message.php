<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\LengthPager;

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
    * get all messages by userId
    * @param  int $id
	* @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAll($id, $count)
	{
		$items = self::selectRaw(
				'*,
				CASE
						WHEN user_otprav = ' . $id . ' 
							THEN user_poluchil
						WHEN user_poluchil = ' . $id . ' 
							THEN user_otprav 
					END as user_id,
			COUNT(user_otprav) as count_messages')
		->where(function ($query) use ($id)
		{
			$query->where('user_otprav', $id);
			$query->where('user_otprav_del', 0);
		})
		->Orwhere (function ($query) use ($id) 
		{
			$query->where('user_poluchil', $id);
			$query->where('user_poluchil_del', 0);
		})
		->groupBy ('user_id')
		->orderBy('time', 'desc')
		->paginate($count);

		$items = LengthPager::makeLengthAware($items, $items->total(), $count);

		if (empty ($items)) return null;
		return $items;
	}

	/**
    * get all messages by userId and $userAutorithationId
    * @param  int $userId
	* @param  int $userAuthId
	* @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getAllByUser($userId, $userAuthId, $count)
	{
		$items = self::selectRaw(
				'*,
				CASE
						WHEN user_otprav = ' . $userAuthId . ' 
							THEN user_poluchil
						WHEN user_poluchil = ' . $userAuthId . ' 
							THEN user_otprav 
					END as user_id')
		->where(function ($query) use ($userAuthId, $userId)
		{
			$query->where('user_otprav', $userAuthId);
			$query->where('user_poluchil', $userId);
			$query->where('user_otprav_del', 0);
		})
		->Orwhere (function ($query) use ($userAuthId, $userId) 
		{
			$query->where('user_poluchil', $userAuthId);
			$query->where('user_otprav', $userId);
			$query->where('user_poluchil_del', 0);
		})
		->orderBy('time', 'asc')
		->paginate($count);

		if (empty ($items)) return null;
		return $items;
	}

	/**
    * get all messages by userId and $userAutorithationId
    * @param  int $userId
	* @param  int $userAuthId
	* @param  int $count
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getForUser($userId, $userAuthId)
	{
		$items = self::select('*')
		->where(function ($query) use ($userId, $userAuthId)
		{
			$query->where('user_otprav', $userId);
			$query->where('user_poluchil', $userAuthId);
		})
		->Orwhere (function ($query) use ($userId, $userAuthId) 
		{
			$query->where('user_poluchil', $userId);
			$query->where('user_otprav', $userAuthId);
		})
		->get();
		return $items;
	}

	/**
    * get a message by id
    * @param  int $id
    * @return \Illuminate\Database\Eloquent\Collection
    */
	public static function getById($id)
	{
		$item = self::select('*')
		->where('message_id', $id)
		->first();

		return $item;
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