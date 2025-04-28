<?php

namespace App\Repositories;

use App\Interfaces\AnketVisitInterface;
use App\Models\AnketVisit;

class AnketVisitRepository implements AnketVisitInterface
{

	protected $fields = [];

	/**
	 * set fields
	 * @param  array $fields
	 * @return AnketVisitRepository;
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;
		return $this;
	}

	/**
	 * get fields
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * get visits of the new users
	 * @param  \Illuminate\Database\Eloquent\Collection  $user
	 * @return \Illuminate\Database\Eloquent\Collection 
	 */
	public function visitsNew($user)
	{
		$items = AnketVisit::select('*')
			->where('user_id_prosm', $user->id)
			->where('create_time', '>', $user->lastvisit_views)
			->get();
		return $items;
	}

	/**
	 * get user visits over userId
	 * @param  int  $int
	 * @param  int  $days
	 * @param  int  $userId
	 * @return \Illuminate\Database\Eloquent\Collection 
	 */
	public function getVisitsByUserId($id, $days, $userId = 0)
	{
		$time = \Carbon\Carbon::now()->subDays($days)->toArray();
		$items = AnketVisit::select('*')
			->where('user_id_prosm', $id)
			->where('create_time', '>', $time['timestamp']);

		if ($userId > 0)
			$items->where('user_id', $userId);

		$items = $items->get();
		return $items;
	}

	/**
	 * update user visits on the page
	 * @param  int  $userId
	 * @param  int  $days
	 * @param  int  $userAuthId
	 * @return int 
	 */
	public function update($userId, $days, $userAuthId)
	{
		$ankVisits	= $this->getVisitsByUserId($userId, $days, $userAuthId);
		$visits		= $ankVisits->count();
		if ($visits == 0 && $userAuthId != $userId) {
			$this->insertVisit($userId);
			$this->removeOld($days);
		} elseif ($visits > 0 && $userAuthId != $userId) {
			$this->updateVisit($userId);
		}
		return $visits;
	}


	/**
	 * update user visits
	 * @param  int  $id
	 * @return void 
	 */
	public function updateVisit($id)
	{
		$user 	= \Auth::user();
		if (empty($user)) abort(404);
		$aFields = [
			'user_id_prosm' => $id,
			'user_id' 	=> $user->id
		];
		$this->setFields($aFields);
		try {
			$ankVisits = $this->getByFields();
			if ($ankVisits->count() > 0) {
				$ankVisits->create_time = time();
				$ankVisits->save();
			}
		} catch (\Exception $e) {
			throw new \Exception('Failed to update user visits. ' . $e->getMessage());
		}
	}

	/**
	 * get user visits over user fields
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getByFields()
	{
		$fields = $this->getFields();
		if (empty($fields)) return null;
		$items = AnketVisit::select('*');
		foreach ($fields as $k => $v) {
			$items->where($k, $v);
		}
		$items = $items->get();

		if (count($items) == 1)
			$items = $items[0];
		return $items;
	}

	/**
	 * remove all visits for the user from the model
	 * @param  int $userId
	 * @return void
	 */
	public function destroyAllByUserId($userId)
	{
		$visits = $this->setFields(['user_id' => $userId])->getByFields();
		foreach ($visits as $item) {
			$item->delete();
		}
	}

	/**
	 * insert user visite
	 * @param  int  $id
	 * @return void 
	 */
	public static function insertVisit($id)
	{
		$user 	= \Auth::user();
		if (empty($user)) abort(404);
		try {
			$aFields = [
				'user_id_prosm'		=> $id,
				'user_id'			=> $user->id,
				'create_time'			=> time()
			];

			$oAnketVisit = new AnketVisit($aFields);
			$oAnketVisit->save();
		} catch (\Exception $e) {
			throw new \Exception('Failed to create user visit. ' . $e->getMessage());
		}
	}

	/**
	 * remove old visits
	 * @param  int  $days
	 * @return void 
	 */
	public static function removeOld($days)
	{
		$time = \Carbon\Carbon::now()->subDays($days)->toArray();
		try {
			AnketVisit::where('create_time', '<', ($time['timestamp']))->delete();
		} catch (\Exception $e) {
			throw new \Exception('Failed to remove old user visits: ' . $e->getMessage());
		}
	}
}
