<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CityInterface;
use App\Interfaces\RegionInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\UserInterface;
use App\Requests\SearchRequest;
use App\Requests\UserBestRequest;
use App\Services\AnkService;
use App\Services\FormatService;
use App\Services\SearchService;
use App\Filters\UserFilter;
use App\Filters\UserBestFilter;
use App\Fields\SearchField;

class AnketController extends Controller
{
	public $countPerPage 	= 10;
	public $countNewFaces 	= 5;

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
	)
	{
	}

	/**
	* show the page with the must populars profiles
	* @param  string  $sex
	* @return \Illuminate\Http\Response
	*/
	public function getPopularAnkets($sex = 'women')
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$popSex				= $sex == 'men' ? 'мужчины' : 'женщины';
		$ankets				= $this->userRepository->getPopul($this->countPerPage, $s);
		$page				= $ankets->currentPage();
		return response()->view('ankets.popular_search',
		[
			'popSex'		=> $popSex,
			'sex'			=> $sex,
			'page'			=> $page,
			'ankets'		=> $ankets
		]);
	}

	/**
	* show the page with profiles who celebrates a birthday today
	* @return \Illuminate\Http\Response
	*/
	public function getBirthdayAnkets()
	{
		$ankets				= $this->userRepository->getBirthday($this->countPerPage);
		$page				= $ankets->currentPage();
		return response()->view('ankets.birthday_search',
		[
			'page'			=> $page,
			'ankets'		=> $ankets
		]);
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
		$ankets				= $this->userRepository->getBySearch($filter, $request, 'user_top100');
		$page				= $ankets->currentPage();
		$user = Auth::user();
		$user = !empty($user) ? $user->load(['visits']) : null;
		return response()->view('ankets.best',
		[
			'page'				=> $page,
			'ankets'			=> $ankets,
			'titleId'			=> $sex == 'men' ? 'Лучшие парни' : 'Лучшие девушки',
			'countSearchAnkStr' => (new AnkService($ankets))->getFoundStr($this->countPerPage),
			'user'				=> $user
		]);
	}

	/**
	* show the page with views
	* @return \Illuminate\Http\Response
	*/
	public function getViews()
	{
		$ankets						= $this->userRepository->getViews($this->countPerPage);
		$page						= $ankets->currentPage();
		$user						= Auth::user();
		$user->user_lastvisit_views = time();
		$user->update();
		return response()->view('ankets.views',
		[
			'page'			=> $page,
			'ankets'		=> $ankets
		]);
	}

	/**
	* show the page with profiles
	* @param  string  $sex
	* @param  int  $op
	* @return \Illuminate\Http\Response
	*/
	public function getAnkets(FormatService $formService, $sex = '', $op = '')
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$opt				= $formService->prepareAnketTitles($sex, $op);
		if (empty($sex) && empty($op))
		{
			$ankets 			= $this->userRepository->newFaces($this->countNewFaces);
		} else
		{
			$ankets 			= $this->userRepository->getOp($this->countPerPage, $s, $opt);
			$page 				= $ankets->currentPage();
			$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr($this->countPerPage);
		}
		return response()->view('ankets.id',
		[
			'popSex'			=> $opt['ankTitle'],
			'ankTitleId'		=> $opt['ankTitleId'],
			'sex'				=> $sex,
			'page'				=> !empty($page) ? $page : 1,
			'ankets'			=> $ankets,
			'countSearchAnkStr' => !empty($countSearchAnkStr) ? $countSearchAnkStr : ''
		]);
	}

	/**
	* show the page with profiles by filter
	* @param  SearchRequest  $request
	* @param  UserFilter  $filter
	* @param  SearchService  $search
	* @param  SearchField $fields
	* @return \Illuminate\Http\Response
	*/
	public function getBySearch(SearchRequest $request, UserFilter $filter, SearchService $search, SearchField $fields ): \Illuminate\Http\Response
    {
		$ankets				= $this->userRepository->getBySearch($filter, $request);
		$critsSearch		= $search->getSearchText($ankets, $request->validated());
		$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr($request->anket_per_page);
		return response()->view('ankets.search',
		[
			'fields'			=> $fields->get(),
			'isSend'			=> isset($request->send) ? 'Y' : 'N',
			'critsSearch'		=> $critsSearch,
			'ankets'			=> $ankets,
			'countSearchAnkStr'	=> $countSearchAnkStr
		]);
	}
}
