<?php

namespace App\Http\Controllers;

use App\Requests\VoteRequest;
use App\Services\EvaluationService;
use Illuminate\Http\JsonResponse;

class EvaulationController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected EvaluationService $service) {}


	/**
	 * store vote for the user
	 * @param VoteRequest $request
	 * @param int $userId
	 * @return JsonResponse
	 */
	public function store(VoteRequest $request, int $userId): JsonResponse
	{
		$this->service->vote($userId, request()->user(), $request->validated());
		return response()->json(['success' => true]);
	}
}