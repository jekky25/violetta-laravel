<?php

namespace App\Http\Controllers;

use App\Requests\UserBirthdayRequest;
use App\Filters\UserBirthdayFilter;
use App\Requests\UserViewsRequest;
use App\Filters\UserViewsFilter;
use App\Services\ProfileFeedService;

class ProfileFeedController extends Controller
{
/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected ProfileFeedService $service) {}

	/**
	 * show the page with profiles who celebrates a birthday today
	 * @param UserBirthdayRequest $request
	 * @param UserBirthdayFilter $filter
	 * @return \Illuminate\Http\Response
	 */
	public function birthday(UserBirthdayRequest $request, UserBirthdayFilter $filter)
	{
		return view('ankets.birthday_search', ['ankets'	=> $this->service->getBirthday($filter, $request)]);
	}

	/**
	 * show the page with views
	 * @return \Illuminate\Http\Response
	 */
	public function views(UserViewsRequest $request, UserViewsFilter $filter)
	{
		$user = request()->user();
		return view('ankets.views',['ankets' => $this->service->getViews($filter, $request, $user)]);
	}
}
