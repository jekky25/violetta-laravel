<?php

namespace App\Repositories;

use App\Interfaces\AnketVisitInterface;
use App\Models\AnketVisit;

class AnketVisitRepository implements AnketVisitInterface {

	protected $fields = [];

	/**
	* set fields
	* @param  array $fields
	* @return void
	*/
	public function setFields($fields)
	{
		$this->fields = $fields;
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
		->where('user_id_prosm', $user->user_id)
		->where('ank_time', '>', $user->user_lastvisit_views)
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
		->where('ank_time', '>', $time['timestamp']);

		if ($userId > 0)
			$items->where('ank_user_id', $userId);

		$items = $items->get();
		return $items;
	}

	/**
	* update user visits
	* @param  int  $id
	* @return void 
	*/
	public function updateVisit ($id)
	{
		$user 	= \Auth::user();
		if (empty ($user)) abort (404);
		$aFields = [
			'user_id_prosm' => $id,
			'ank_user_id' 	=> $user->user_id
		];
		$this->setFields($aFields);

		try {
			$ankVisits = $this->getByFields ();
			if (!empty($ankVisits))
			{
				$ankVisits->ank_time = time();
				$ankVisits->save();
			}
        } catch (\Exception $e) {
            throw new \Exception('Failed to update user visits. '.$e->getMessage());
        }
	}

	/**
	* get user visits over user fields
	* @return \Illuminate\Database\Eloquent\Collection
	*/
	public function getByFields ()
	{
		$fields = $this->getFields();
		if (empty($fields)) return null;
		$items = AnketVisit::select('*');
		foreach ($fields as $k => $v)
		{
			$items->where ($k, $v);
		}
		$items = $items->get();

		if (count($items) == 1)
			$items = $items[0];
		return $items;
	}
}