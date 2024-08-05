<?php

namespace App\Repositories;

use App\Interfaces\AnketEvaluationInterface;
use App\Models\AnketEvaluation;

class AnketEvaluationRepository implements AnketEvaluationInterface {
	/**
	* get evaluation
	* @param  int  $userIdAct
	* @param  int  $userId
	* @return \Illuminate\Database\Eloquent\Collection 
	*/
	public static function getEvaluations($userIdAct, $userId)
	{
		$items = AnketEvaluation::select('*')
		->where('user_id', $userIdAct)
		->where('user_id_ocenka', $userId)
        ->get();
    	return $items;
	}

}