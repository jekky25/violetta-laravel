<?php

namespace App\Http\Controllers;

use App\Fields\SearchField;
use App\Filters\UserFilter;
use App\Services\AnkService;
use App\Filters\AnketsFilter;
use App\Requests\SearchRequest;
use App\Requests\AnketsRequest;
use App\Services\FormatService;
use App\Services\SearchService;
use App\Filters\UserBestFilter;
use App\Filters\UserViewsFilter;
use App\Requests\UserBestRequest;
use App\Interfaces\CityInterface;
use App\Interfaces\UserInterface;
use App\Requests\UserViewsRequest;
use App\Filters\UserPopularFilter;
use App\Filters\UserBirthdayFilter;
use App\Interfaces\RegionInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\CountryInterface;
use App\Requests\UserPopularRequest;
use Illuminate\Support\Facades\Auth;
use App\Requests\UserBirthdayRequest;
use App\Http\Resources\Profile\ProfileShortResource;

class AnketController extends Controller
{
	public $countPerPage 	= 10;
	public $countNewFaces 	= 5;
	public $countOne		= 1;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected CityInterface $cityRepository,
		protected RegionInterface $regionRepository,
		protected CountryInterface $countryRepository,
		protected UserInterface $userRepository
	) {}

	/**
	 * show the page with the must populars profiles
	 * @param UserPopularRequest $request
	 * @param UserPopularFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function getPopularAnkets(UserPopularRequest $request, UserPopularFilter $filter, $sex = 'women')
	{
		$ankets				= $this->userRepository->getBySearch($filter, $request);

		return response()->view(
			'ankets.popular_search',
			[
				'popSex'		=> $sex == 'men' ? 'мужчины' : 'женщины',
				'sex'			=> $sex,
				'page'			=> $ankets->currentPage(),
				'ankets'		=> $ankets
			]
		);
	}

	/**
	 * show the page with profiles who celebrates a birthday today
	 * @param UserBirthdayRequest $request
	 * @param UserBirthdayFilter $filter
	 * @return \Illuminate\Http\Response
	 */
	public function getBirthdayAnkets(UserBirthdayRequest $request, UserBirthdayFilter $filter)
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
	 * show the page with the best proprofiles
	 * @param UserBestRequest $request
	 * @param UserBestFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function getBestAnkets(UserBestRequest $request, UserBestFilter $filter, $sex)
	{
		$ankets				= $this->userRepository->getBySearch($filter, $request, 'top100');
		$page				= $ankets->currentPage();
		$user = Auth::user();
		$user = !empty($user) ? $user->load(['visits']) : null;
		return response()->view(
			'ankets.best',
			[
				'page'				=> $page,
				'ankets'			=> $ankets,
				'titleId'			=> $sex == 'men' ? 'Лучшие парни' : 'Лучшие девушки',
				'countSearchAnkStr' => (new AnkService($ankets))->getFoundStr($this->countPerPage),
				'user'				=> $user
			]
		);
	}

	/**
	 * show the page with views
	 * @return \Illuminate\Http\Response
	 */
	public function getViews(UserViewsRequest $request, UserViewsFilter $filter)
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

	/**
	 * show the page with profiles
	 * @param  string  $sex
	 * @param  int  $op
	 * @return \Illuminate\Http\Response
	 */
	public function getAnkets(AnketsRequest $request, AnketsFilter $filter, FormatService $formService, $sex = '', $op = '')
	{
		$opt				= $formService->prepareAnketTitles($sex, $op);
		if (empty($sex) && empty($op)) {
			$ankets 			= $this->userRepository->newFaces($this->countNewFaces);
		} else {
			$ankets				= $this->userRepository->getBySearch($filter, $request);
			$page 				= $ankets->currentPage();
			$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr($this->countPerPage);
		}
		return response()->view(
			'ankets.id',
			[
				'popSex'			=> $opt['ankTitle'],
				'ankTitleId'		=> $opt['ankTitleId'],
				'sex'				=> $sex,
				'page'				=> !empty($page) ? $page : 1,
				'ankets'			=> $ankets,
				'countSearchAnkStr' => !empty($countSearchAnkStr) ? $countSearchAnkStr : ''
			]
		);
	}

	/**
	 * show the page with profiles by filter
	 * @param  SearchRequest  $request
	 * @param  UserFilter  $filter
	 * @param  SearchService  $search
	 * @param  SearchField $fields
	 * @return \Illuminate\Http\Response
	 */
	public function getBySearch(SearchRequest $request, UserFilter $filter, SearchService $search, SearchField $fields): \Illuminate\Http\Response
	{
		$ankets				= $this->userRepository->getBySearch($filter, $request);
		$critsSearch		= $search->getSearchText($ankets, $request->validated());
		$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr($request->anket_per_page);
		return response()->view(
			'ankets.search',
			[
				'fields'			=> $fields->get(),
				'isSend'			=> isset($request->send) ? 'Y' : 'N',
				'critsSearch'		=> $critsSearch,
				'ankets'			=> $ankets,
				'countSearchAnkStr'	=> $countSearchAnkStr
			]
		);
	}

	/**
	 * Get a profile from top100
	 * @param  int  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function getTop100($sex = WOMEN)
	{
		$profiles = $this->userRepository->getTop100($sex, $this->countOne);
		return new ProfileShortResource($profiles);
	}
}
