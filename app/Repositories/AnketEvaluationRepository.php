<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Interfaces\AnketEvaluationInterface;
use App\Models\AnketEvaluation;

class AnketEvaluationRepository implements AnketEvaluationInterface
{
	private $evaluations;
	/**
	 * get evaluation
	 * @param  int  $userIdAct
	 * @param  int  $userId
	 * @return \Illuminate\Database\Eloquent\Collection 
	 */
	public function getEvaluations($userIdAct, $userId)
	{
		$this->evaluations = AnketEvaluation::select('*')
			->where('user_id', $userIdAct)
			->where('user_id_ocenka', $userId)
			->get();
		return $this->evaluations;
	}

	/**
	 * and evaluation and update
	 * @param  Illuminate\Http\Request $request
	 * @param  int $userActiveId
	 * @param  int $id
	 * @return bool|Illuminate\Http\RedirectResponse
	 */
	public function getEvaluationWithUpdate(Request $request, $userActiveId, $id)
	{
		if ($this->evaluations->count() > 0) return true;
		$vote 	= isset($request->golos) ? (int)$request->golos : 0;
		$vote 	= $vote > 5 ? 5 : $vote;
		if (!$this->isSendVoice($request, $vote)) return false;
		if ($userActiveId != $id) {
			$aFields = [
				'user_id'			=> $userActiveId,
				'user_id_ocenka'	=> $id,
				'ball'				=> $vote,
				'time'				=> time()
			];
			$this->create($aFields);
		}
		$voteSum = $this->getSum($id);
		if ($voteSum > 0) {
			$anket = (new userRepository)->getJustById($id);
			$anket->rating = $voteSum;
			$anket->update();
		}
		return redirect()->route(Route::currentRouteName(), $id)->with('success', 'Спасибо. Ваш голос учтен.');
	}

	/**
	 * check voice route
	 * @param  Illuminate\Http\Request $request
	 * @param  int $vote
	 * @return bool
	 */
	public function isSendVoice($request, $vote)
	{
		return ($request->has('send_golos') && $vote > 0);
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
			->sum('ball', 'sum_ank');
		return (int)$item;
	}

	/**
	 * create an evaluation
	 * @param  array $request
	 * @return void
	 */
	public function create($request)
	{
		try {
			AnketEvaluation::create($request);
		} catch (\Exception $e) {
			throw new \Exception('Failed to create an Profile Evaluation ' . $e->getMessage());
		}
	}
}
