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
}