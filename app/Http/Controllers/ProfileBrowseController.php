<?php

namespace App\Http\Controllers;

use App\Filters\AnketsFilter;
use App\Services\FormatService;
use App\Requests\AnketsRequest;
use App\Requests\UserPopularRequest;
use App\Filters\UserPopularFilter;
use App\Requests\UserBestRequest;
use App\Filters\UserBestFilter;
use App\Http\Resources\Profile\ProfileShortResource;
use App\Services\ProfileBrowseService;

class ProfileBrowseController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected ProfileBrowseService $service
	) {}


	/**
	 * show the page with profiles
	 * @param  ?string  $sex
	 * @param  ?int  $age
	 * @return \Illuminate\Http\Response
	 */
	public function getList(AnketsRequest $request, AnketsFilter $filter, ?string $sex = null, ?int $age = null)
	{
		return response()->view('ankets.id', $this->service->getList($request, $filter, $sex, $age));
	}

	/**
	 * show the page with the must populars profiles
	 * @param UserPopularRequest $request
	 * @param UserPopularFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function popular(UserPopularRequest $request, UserPopularFilter $filter, string $sex = 'women')
	{
		return response()->view('ankets.popular_search', $this->service->getPopular($request, $filter, $sex));
	}

	/**
	 * show the page with the best proprofiles
	 * @param UserBestRequest $request
	 * @param UserBestFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function best(UserBestRequest $request, UserBestFilter $filter, string $sex)
	{
		return response()->view('ankets.best', $this->service->getBest($request, $filter, $sex));
	}

	/**
	 * Get a profile from top100
	 * @param  int  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function top100(int $sex = WOMEN)
	{
		$profiles = $this->service->getTop100($sex, config('pagination.profiles_VIP_one'));
		return new ProfileShortResource($profiles);
	}
}
