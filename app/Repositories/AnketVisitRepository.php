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
	public static function visitsNew($user)
	{
		$items = AnketVisit::select('*')
		->where('user_id_prosm', $user->user_id)
		->where('ank_time', '>', $user->user_lastvisit_views)
        ->get();
    	return $items;
	}
}