<?php

namespace App\Http\Controllers;

use App\Filters\AnketsFilter;
use App\Services\AnkService;
use App\Services\FormatService;
use App\Requests\AnketsRequest;
use App\Requests\UserPopularRequest;
use App\Filters\UserPopularFilter;
use App\Interfaces\UserInterface;
use App\Requests\UserBestRequest;
use App\Filters\UserBestFilter;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Profile\ProfileShortResource;

class ProfileBrowseController extends Controller
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
	 * show the page with profiles
	 * @param  ?string  $sex
	 * @param  ?int  $op
	 * @return \Illuminate\Http\Response
	 */
	public function getList(AnketsRequest $request, AnketsFilter $filter, FormatService $formService, ?string $sex = null, ?int $op = null)
	{
	    $sex = $sex ?? 'women';
    	$age = $age ?? null;

		$opt				= $formService->prepareAnketTitles($sex, $op);
		if (empty($sex) && empty($op)) {
			$ankets 			= $this->userRepository->newFaces(config('pagination.profiles_under_menu'));
		} else {
			$ankets				= $this->userRepository->getBySearch($filter, $request);
			$page 				= $ankets->currentPage();
			$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr();
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
	 * show the page with the must populars profiles
	 * @param UserPopularRequest $request
	 * @param UserPopularFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function popular(UserPopularRequest $request, UserPopularFilter $filter, $sex = 'women')
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
	 * show the page with the best proprofiles
	 * @param UserBestRequest $request
	 * @param UserBestFilter $filter
	 * @param  string  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function best(UserBestRequest $request, UserBestFilter $filter, $sex)
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
				'countSearchAnkStr' => (new AnkService($ankets))->getFoundStr(),
				'user'				=> $user
			]
		);
	}

	/**
	 * Get a profile from top100
	 * @param  int  $sex
	 * @return \Illuminate\Http\Response
	 */
	public function top100($sex = WOMEN)
	{
		$profiles = $this->userRepository->getTop100($sex, config('pagination.profiles_VIP_one'));
		return new ProfileShortResource($profiles);
	}
}
