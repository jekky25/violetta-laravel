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
	public function getEvaluations($userIdAct, $userId)
	{
		$items = AnketEvaluation::select('*')
		->where('user_id', $userIdAct)
		->where('user_id_ocenka', $userId)
        ->get();
    	return $items;
	}

	/**
	* get the summ of all evaluations
	* @param  int  $id
	* @return \Illuminate\Database\Eloquent\Collection 
	*/
	public function getSum($id)
	{
		$item = AnketEvaluation::select(['*'])
		->where('user_id_ocenka', $id)
		->sum('ball','sum_ank');
		return (int)$item;
	}

	/**
	* create an evaluation
	* @param  array $request
	* @return void
	*/	
	public function create($request) {
		try {
			AnketEvaluation::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create an Profile Evaluation '.$e->getMessage());
		}
	}
}