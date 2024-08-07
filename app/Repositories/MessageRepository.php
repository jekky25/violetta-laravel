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
	public static function getAll($id, $count)
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
}