<?php

namespace App\Repositories;

use App\Interfaces\AnketVisitInterface;
use App\Models\AnketVisit;

class AnketVisitRepository implements AnketVisitInterface {
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
}