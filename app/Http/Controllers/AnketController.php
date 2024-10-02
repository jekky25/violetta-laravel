<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CityInterface;
use App\Interfaces\RegionInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\UserInterface;
use App\Requests\SearchRequest;
use App\Services\DataService;
use App\Services\AnkService;
use App\Services\FormatService;
use App\Services\SearchService;

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
	public function getPopularAnkets ($sex = 'women')
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$popSex				= $sex == 'men' ? 'мужчины' : 'женщины';
		$ankets				= $this->userRepository->getPopul($this->countPerPage, $s);
		$page				= $ankets->currentPage();
		return response()->view ('ankets.popular_search', 
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
	public function getBirthdayAnkets ()
	{
		$ankets				= $this->userRepository->getBirthday($this->countPerPage);
		$page				= $ankets->currentPage();
		return response()->view ('ankets.birthday_search', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets
		]);
	}

	/**
	* show the page with the best proprofiles
	* @param  string  $sex
	* @return \Illuminate\Http\Response
	*/
	public function getBestAnkets ($sex)
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$ankets				= $this->userRepository->getBest($this->countPerPage, $s);
		$page				= $ankets->currentPage();
		$titleId = $sex == 'men' ? 'Лучшие парни' : 'Лучшие девушки';
		$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr ($this->countPerPage);
		$user = Auth::user();
		$user = !empty($user) ? $user->load(['visits']) : null;
		return response()->view ('ankets.best', 
		[
			'page'				=> $page,
			'ankets'			=> $ankets,
			'titleId'			=> $titleId,
			'countSearchAnkStr' => $countSearchAnkStr,
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
			'countSearchAnkStr' => !empty ($countSearchAnkStr) ? $countSearchAnkStr : ''
		]);
	}
	
	/**
	* show the page with profiles by filter
	* @param  Request  $request 
	* @return \Illuminate\Http\Response
	*/
	public function getBySearch(SearchRequest $request, SearchService $search, FormatService $formService)
	{
		$params				= $request->validated();
		$ankets				= $this->userRepository->getBySearch($request, $params);
		$critsSearch		= $search->getSearchText($ankets, $params);
		$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr($params['anket_per_page']);
		$ages				= DataService::getAges();
		$countries			= $this->countryRepository->getAll();
		$heights			= $formService->getHeights();
		$weights			= $formService->getWeights();
		$body				= $formService->BlockSelect('body',BODY_CLASS,0,0);
		$hairType			= $formService->BlockSelect('hair_type',HAIR_TYPE_CLASS,0,0);
		$eyes				= $formService->BlockSelect('eyes',EYES_CLASS,0,0);
		return response()->view('ankets.search', 
		[
			'ages'				=> $ages,
			'countries'			=> $countries,
			'heights'			=> $heights,
			'weights'			=> $weights,
			'body'				=> $body,
			'hairType'			=> $hairType,
			'eyes'				=> $eyes,
			'isSend'			=> isset ($request->send) ? 'Y' : 'N',
			'critsSearch'		=> $critsSearch,
			'ankets'			=> $ankets,
			'photo'				=> $params['photo'],
			'countSearchAnkStr'	=> $countSearchAnkStr
		]);
	}
}