<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class StatisticsController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserRepository $userRepository
	) {}

	/**
	 * just a stub for the forum
	 * @return bool
	 */
	public function get()
	{
		$statProfiles	= $this->userRepository->getStatistic();
		return collect($statProfiles);
	}
}
