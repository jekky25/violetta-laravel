<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Forum\ForumShortResource;
use App\Services\ForumService;

class ForumController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected ForumService $service
	) {}

	/**
	 * just a stub for the forum
	 * @return bool
	 */
	public function index()
	{
		return false;
	}

	/**
	 * get a top block with the latest topics
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function getTop()
	{
		return ForumShortResource::collection($this->service->getTop());
	}
}
