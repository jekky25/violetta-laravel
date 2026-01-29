<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ForumInterface;
use App\Http\Resources\Forum\ForumShortResource;

class ForumController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected ForumInterface $forumRepository
	) {}

	/**
	 * just a stub for the forum
	 * @return bool
	 */
	public function index()
	{
		return false;
	}

	public function getTop()
	{
		$forums = $this->forumRepository->getTop();
		return ForumShortResource::collection($forums);
	}
}
