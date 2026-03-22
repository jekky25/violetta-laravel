<?php

namespace App\Repositories;

use App\Interfaces\MessageInterface;
use App\Models\Message;
use App\Models\User;
use App\Services\LengthPager;

class MessageRepository implements MessageInterface
{
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
						WHEN sent_user_id = ' . $id . ' 
							THEN received_user_id
						WHEN received_user_id = ' . $id . ' 
							THEN sent_user_id 
					END as user_id,
			COUNT(sent_user_id) as count_messages'
		)
			->where(function ($query) use ($id) {
				$query->senderId($id);
				$query->sentNotDeleted();
			})
			->Orwhere(function ($query) use ($id) {
				$query->receiverId($id);
				$query->receiveNotDeleted();
			})
			->groupBy('user_id')
			->orderBy('create_time', 'desc')
			->paginate($count);

		$items = LengthPager::makeLengthAware($items, $items->total(), $count);
		return !empty($items) ? $items : null;
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
						WHEN sent_user_id = ' . $userAuthId . ' 
							THEN received_user_id
						WHEN received_user_id = ' . $userAuthId . ' 
							THEN sent_user_id 
					END as user_id'
		)
			->where(function ($query) use ($userAuthId, $userId) {
				$query->senderId($userAuthId);
				$query->receiverId($userId);
				$query->sentNotDeleted();
			})
			->Orwhere(function ($query) use ($userAuthId, $userId) {
				$query->receiverId($userAuthId);
				$query->senderId($userId);
				$query->receiveNotDeleted();
			})
			->orderBy('create_time', 'asc')
			->paginate($count);

		if (empty($items)) return null;
		foreach ($items as &$item) {
			$this->resetNewMess($item, $userAuthId);
		}
		return $items;
	}

	/**
	 * reset a new mess flag
	 * @param  App\Models\Message $item
	 * @param  int $userAuthId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function resetNewMess(&$item, $userAuthId)
	{
		if ($item->is_new == 1 && $item->received_user_id == $userAuthId) {
			$item->is_new = 0;
			$item->update();
		}
		return $item;
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
		return Message::select('*')
			->where(function ($query) use ($userId, $userAuthId) {
				$query->senderId($userId);
				$query->receiverId($userAuthId);
			})
			->Orwhere(function ($query) use ($userId, $userAuthId) {
				$query->receiverId($userId);
				$query->senderId($userAuthId);
			})
			->get();
	}

	/**
	 * get a message by id
	 * @param  int $id
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getById($id)
	{
		return Message::select('*')->whereKey($id)->firstOrFail();
	}

	/**
	 * get all new messages for $user
	 * @param  User $user
	 * @param  int $count 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getNewsByUsers(User $user, int $count)
	{
		$messages 			= $this->getAll($user->id, $count);
		if ($messages->count() == 0) return $messages;
		$users = [];
		foreach ($messages as $item) {
			$users[] = $item->user_id;
		}

		if (!empty($users)) {
			$items = Message::select('*')
				->receiverId($user->id)
				->whereIn('sent_user_id', $users)
				->new()
				->get();
		}
		foreach ($messages as &$_message) {
			$_message->is_new = 0;
			foreach ($items as $item) {
				if ($_message->user_id == $item->sent_user_id) {
					$_message->is_new = $item->is_new;
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
		return Message::select('*')->senderId($id)->createdFrom((time() - 5 * 60))->get();
	}

	/**
	 * create a message
	 * @param  array $request
	 * @return void
	 */
	public function store($request)
	{
		try {
			Message::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create a message ' . $e->getMessage());
		}
	}

	/**
	 * delete a message
	 * @param  Message $message
	 * @param  int $userAuthId
	 * @return void
	 */
	public function delete($message, $userAuthId)
	{
		try {
			$message->sent_is_deleted		= $message->sent_user_id == $userAuthId ? 1 : $message->sent_is_deleted;
			$message->received_is_deleted	= $message->sent_user_id == $userAuthId ? $message->received_is_deleted : 1;
			$message->update();
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Message . ' . $e->getMessage());
		}
	}

	/**
	 * delete a message
	 * @param  array $userIds
	 * @param  int $userAuthId
	 * @return void
	 */
	public function deleteSelected($userIds, $userAuthId)
	{
		try {
			foreach ($userIds as $userId) {
				$messages = $this->getForUser($userId, $userAuthId);
				if ($messages->count() == 0) continue;
				foreach ($messages as $message) {
					$this->delete($message, $userAuthId);
				}
			}
		} catch (\Exception $e) {
			throw new \Exception('Failed to delete Message . ' . $e->getMessage());
		}
	}
}
