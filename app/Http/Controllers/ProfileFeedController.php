<?php

namespace App\Http\Controllers;

use App\Requests\UserBirthdayRequest;
use App\Filters\UserBirthdayFilter;
use App\Interfaces\UserInterface;
use App\Requests\UserViewsRequest;
use App\Filters\UserViewsFilter;
use Illuminate\Support\Facades\Auth;

class ProfileFeedController extends Controller
{
/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $userRepository
	) {}

	/**
	 * show the page with profiles who celebrates a birthday today
	 * @param UserBirthdayRequest $request
	 * @param UserBirthdayFilter $filter
	 * @return \Illuminate\Http\Response
	 */
	public function birthday(UserBirthdayRequest $request, UserBirthdayFilter $filter)
	{
		$ankets				= $this->userRepository->getBySearch($filter, $request);

		return response()->view(
			'ankets.birthday_search',
			[
				'page'			=> $ankets->currentPage(),
				'ankets'		=> $ankets
			]
		);
	}

	/**
	 * show the page with views
	 * @return \Illuminate\Http\Response
	 */
	public function views(UserViewsRequest $request, UserViewsFilter $filter)
	{
		$ankets						= $this->userRepository->getBySearch($filter, $request);
		$this->userRepository->update(Auth::user(), ['lastvisit_views' => time()]);
		return response()->view(
			'ankets.views',
			[
				'page'			=> $ankets->currentPage(),
				'ankets'		=> $ankets
			]
		);
	}
}
