<?php

namespace App\Repositories;

use App\Interfaces\MessageInterface;
use App\Models\Message;
use App\Services\LengthPager;

class MessageRepository implements MessageInterface {
	/**
	* get all messages by userId
	* @param  int $id
	* @param  int $count
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getAll($id, $count)
	{
		$items = Message::selectRaw(
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
	public function getAllByUser($userId, $userAuthId, $count)
	{
		$items = Message::selectRaw(
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
	public function getForUser($userId, $userAuthId)
	{
		$items = Message::select('*')
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
	public function getById($id)
	{
		$item = Message::select('*')
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
	public function getNewsByUsers ($messages, $user)
	{
		if ($messages->count() == 0) return $messages;
		$users = [];
		foreach ($messages as $item)
		{
			$users[] = $item->user_id;
		}

		if (!empty($users))
		{
			$items = Message::select('*')
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
	public function getByTimeByUser($id)
	{
		$items = Message::select('*')
		->where('user_otprav', $id)
		->where('time', '>', (time() - 5*60))
		->get();
		return $items;
	}

	/**
	* create a message
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			Message::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a message '.$e->getMessage());
		}
	}
}