<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
	use HasFactory;

	protected $table 		= 'user_messages';
	protected $user;

	public $timestamps 		= false;
	protected $primaryKey 	= 'message_id';

	public function __construct()
    {
        if (empty($this->user)) 
			$this->user	= Auth::user();
    }

	public function getAll($id, $count)
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

		if (empty ($items)) return null;
		return $items;
	}

	public function getAllByUser($userId, $userAuthId, $count)
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

	public function getForUser($userId, $userAuthId)
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