<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CityInterface;
use App\Interfaces\RegionInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Repositories\BodyRepository;
use App\Models\HairType;
use App\Models\Eyes;
use App\Helpers\Helper;
use App\Services\LengthPager;

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
		$s 					= $sex == 'men' ? MEN 		: WOMEN;
		$popSex 			= $sex == 'men' ? 'мужчины' : 'женщины';
		$ankets 			= User::getPopul($this->countPerPage, $s);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		return response()->view ('ankets.popular_search', 
		[
			'popSex'		=> $popSex,
			'sex'			=> $sex,
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

	/**
	* show the page with profiles who celebrates a birthday today
	* @return \Illuminate\Http\Response
	*/
	public function getBirthdayAnkets ()
	{
		$ankets 			= $this->userRepository->getBirthday($this->countPerPage);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		return response()->view ('ankets.birthday_search', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

	/**
	* show the page with the best proprofiles
	* @param  string  $sex
	* @return \Illuminate\Http\Response
	*/
	public function getBestAnkets ($sex)
	{
		$s 					= $sex == 'men' ? MEN 		: WOMEN;
		$ankets 			= User::getBest($this->countPerPage, $s);
		$maxReit 			= User::getMaxReiting($s);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		foreach ($ankets as &$item)
		{
			$item->user_reiting_str 	= Helper::reiting ($item->user_reiting,$maxReit);
			$item->onTop = '<strong>' . ($item->user_sex == 2 ? 'поднялась' : 'поднялся') . '</strong>: ' . Helper::lastVisit($item->user_top100);
		}

		$titleId = $sex == 'men' ? 'Лучшие парни' : 'Лучшие девушки';

		$countSearchAnkStr	= Helper::getFoundStr ($ankets, $this->countPerPage);
		$user = Auth::user();
		$user = !empty($user) ? $user->load(['visits']) : null;

		return response()->view ('ankets.best', 
		[
			'page'				=> $page,
			'ankets'			=> $ankets,
			'pagination'		=> $pagination,
			'titleId'			=> $titleId,
			'countSearchAnkStr' => $countSearchAnkStr,
			'user'				=> $user
		]);
	}

	/**
	* show the page with views
	* @return \Illuminate\Http\Response
	*/
	public function getViews ()
	{
		$ankets 					= User::getViews($this->countPerPage);
		$page 						= $ankets->currentPage();
		$pagination 				= Helper::preparePagination ($ankets->toArray()['links']);
		$user	 					= Auth::user();
		$user->user_lastvisit_views = time();
		$user->update();

		return response()->view ('ankets.views', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

	/**
	* show the page with profiles
	* @param  string  $sex
	* @param  int  $op
	* @return \Illuminate\Http\Response
	*/
	public function getAnkets ($sex = '', $op = '')
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$ankTitle 			= $sex == 'men' ? 'Анкеты: мужчины' : 'Анкеты: женщины';
		$ankTitleId 		= $sex == 'men' ? 'Анкеты мужчин' : 'Анкеты женщин';
		$birthDate			= NULL;
		$birthDate2			= NULL;

		switch ($op) {
			case '20':
				$birthDate 		= Helper::birthAround(20);
				$ankTitle 		.= ', до 20 лет';
				$ankTitleId 	.= ' до 20 лет';
				break;
		
			case '2025':
				$birthDate 		= Helper::birthAround(25);
				$birthDate2		= Helper::birthAround(19);
				$ankTitle 		.= ', 20 - 25 лет';
				$ankTitleId 	.= ' 20 - 25 лет';
				break;
			case '2535':
				$birthDate 		= Helper::birthAround(35);
				$birthDate2		= Helper::birthAround(24);
				$ankTitle 		.= ', 25 - 35 лет';
				$ankTitleId 	.= ' 25 - 35 лет';
				break;
			case "3550":
				$birthDate 		= Helper::birthAround(50);
				$birthDate2 	= Helper::birthAround(34);
				$ankTitle 		.= ', 35 - 50 лет';
				$ankTitleId 	.= ' 35 - 50 лет';
				break;
			case "50":
				$birthDate2	 	= Helper::birthAround(50);
				$ankTitle 		.= ', от 50 лет';
				$ankTitleId 	.= ' от 50 лет';
				break;
		}
		$opt = [
				'birthDate' => $birthDate,
				'birthDate2' => $birthDate2,
			];

		if (empty($sex) && empty($op))
		{
			$ankets 			= $this->userRepository->newFaces($this->countNewFaces);
		} else
		{
			$ankets 			= User::getOp($this->countPerPage, $s, $opt);
			$page 				= $ankets->currentPage();
			$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);
			$countSearchAnkStr	= Helper::getFoundStr ($ankets, $this->countPerPage);
		}

		return response()->view ('ankets.id', 
		[
			'popSex'			=> $ankTitle,
			'ankTitleId'		=> $ankTitleId,
			'sex'				=> $sex,
			'page'				=> !empty($page) ? $page : 1,
			'ankets'			=> $ankets,
			'countSearchAnkStr' => !empty ($countSearchAnkStr) ? $countSearchAnkStr : '',
			'pagination'		=> !empty ($pagination) ? $pagination : ''
		]);
	}
	
	/**
	* show the page with profiles by filter
	* @param  \Illuminate\Http\Request  $request 
	* @return \Illuminate\Http\Response
	*/
	public function getBySearch(Request $request)
	{
		$isSend 	= 'N';
		$critsSearch = '';
		if (isset ($request->send))
		{
			$isSend = 'Y';

			$sex 				= isset ($request->sex) 			? (int)$request->sex 			: 0;
			$findSex 			= isset ($request->find_sex) 		? (int)$request->find_sex 		: 0;
			$photo 				= isset ($request->photo) 			? $request->photo 				: 0;
			$online 			= isset ($request->online)			? $request->online 				: '';
			$online				= $online == 1 || $online == 'on' 	? 1 							: $online;

			$ageMin 			= isset ($request->age_min)			? (int)$request->age_min 		: AGE_MIN;
			$ageMax 			= isset ($request->age_max)			? (int)$request->age_max 		: AGE_MAX;
			$heightMin 			= isset ($request->height_min) 		? (int)$request->height_min 	: HEIGHT_MIN;
			$heightMax 			= isset ($request->height_max)		? (int)$request->height_max 	: HEIGHT_MAX;
			$weightMin 			= isset ($request->weight_min)		? (int)$request->weight_min 	: WEIGHT_MIN;
			$weightMax 			= isset ($request->weight_max)		? (int)$request->weight_max 	: WEIGHT_MAX;
			$country 			= isset ($request->country)			? (int)$request->country 		: 0;
			$region 			= isset ($request->region)			? (int)$request->region 		: 0;
			$city 				= isset ($request->city)			? (int)$request->city 			: 0;
			$body 				= isset ($request->body)			? (int)$request->body 			: 0;
			$hairType 			= isset ($request->hair_type)		? (int)$request->hair_type 		: 0;
			$hairColor			= isset ($request->hair_color)		? (int)$request->hair_color 	: 0;
			$eyes 				= isset ($request->eyes)			? (int)$request->eyes 			: 0;
			$anketPerPage		= isset ($request->anket_per_page)	? (int)$request->anket_per_page : $this->countPerPage;

			$crits				= false;
			$critSex 			= false;
			$critAgeMin 		= false;
			$critAgeMax 		= false;
			$critHeightMin 		= false;
			$critHeightMax 		= false;
			$critWeightMin 		= false;
			$critWeightMax 		= false;
			$critBody 			= false;
			$critHairType 		= false;
			$critEyes 			= false;

			$ankets = User::select('*');
			$ankets->where ('user_active', 1);

			if ($findSex !== 0 && $sex == 0) 
			{
				$ankets->where ('user_sex', $findSex);
				$critSex 	= $findSex == MEN ? '<strong>мужчину</strong>' : '<strong>женщину</strong>';
				$crits 		= true;
			} else if ($findSex == 0 && $sex !== 0) 
			{
				if ($sex == MEN) 
				{
					Helper::queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $ankets);
					Helper::queryBlockOr([WOMEN, GETEROSEXUAL, BISEXUAL], $ankets);
					$CkritSex 	= 'тех, кто ищет <strong>мужчину</strong>';
					$crits 		= true;
					
				} else if ($sex == WOMEN) 
				{
					Helper::queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $ankets);
					Helper::queryBlockOr([MEN, GETEROSEXUAL, BISEXUAL], $ankets);
					$CkritSex 	= 'тех, кто ищет <strong>женщину</strong>';
					$crits 		= true;
				}
			} else if ($findSex !== 0 && $sex !== 0) 
			{
				if ($sex == MEN) 
				{
					if ($findSex == MEN) 
					{
						Helper::queryBlock([MEN, GOMOSEXUAL, BISEXUAL], $ankets);
						$CkritSex 	= '<strong>мужчину</strong>, который ищет <strong>мужчину</strong>';
						$crits 		= true;
					} else if ($findSex == WOMEN) 
					{
						Helper::queryBlock([WOMEN, GETEROSEXUAL, BISEXUAL], $ankets);
						$CkritSex 	= '<strong>женщину</strong>, которая ищет <strong>мужчину</strong>';
						$crits 		= true;
					}
				} else if ($sex == WOMEN) 
				{
					if ($findSex == WOMEN) 
					{
						Helper::queryBlock([WOMEN, GOMOSEXUAL, BISEXUAL], $ankets);
						$CkritSex 	=  '<strong>женщину</strong>, которая ищет <strong>женщину</strong>';
						$crits		= true;
					} else if ($findSex == MEN) 
					{
						Helper::queryBlock([MEN, GETEROSEXUAL, BISEXUAL], $ankets);
						$CkritSex 	=  '<strong>мужчину</strong>, который ищет <strong>женщину</strong>';
						$crits		= true;
					}
				}
			}

			if (!empty($photo))
			{
				$ankets->where ('user_fotos', '>', 0);
				$crits	= true;

			}

			if (!empty ($online)) 
			{
				$crits	= true;
			}

			if ($ageMin > AGE_MIN) 
			{
				$ankets->where ('user_birth_date', '<', Helper::birthAround($ageMin-1));
				$critAgeMin = ' от <strong>' . $ageMin . '</strong>';
				$crits		= true;
			}
	
			if ($ageMax > AGE_MAX) 
			{
				$ankets->where ('user_birth_date', '>', Helper::birthAround($ageMax));
				$critAgeMax = ' до <strong>' . $ageMax . '</strong>';
				$crits		= true;
			}
			if ($heightMin > HEIGHT_MIN) 
			{
				$ankets->where ('user_height', '>=', $heightMin);
				$critHeightMin = ' от <strong>' . $heightMin . '</strong>';
				$crits		= true;
			} 
			if ($heightMax > HEIGHT_MAX) 
			{ 
				$ankets->where ('user_height', '<=', $heightMax);
				$critHeightMax = ' до <strong>' . $heightMax . '</strong>';
				$crits		= true;
			}
	  
			if ($weightMin > WEIGHT_MIN) 
			{
				$ankets->where ('user_weight', '>=', $weightMin);
				$critWeightMin = ' от <strong>' . $weightMin . '</strong>';
				$crits		= true;
			} 
			if ($weightMax > WEIGHT_MAX) 
			{
				$ankets->where ('user_weight', '<=', $weightMax);
				$critWeightMax = ' до <strong>' . $weightMax . '</strong>';
				$crits		= true;
			}

			if ($body > 0) 
			{
				$ankets->where ('user_body', $body);
				$oBody = BodyRepository::getById ($body);
				$critBody = '<br /> телосложение <strong>' . $oBody->name . '</strong>';
				$crits		= true;
			}

			if ($hairType > 0) 
			{
				$ankets->where ('user_hair_type', $hairType);
				$oHairType = HairType::getById ($hairType);
				$critHairType = '<br /> тип волос <strong>' . $oHairType->name . '</strong>';
				$crits		= true;
			}

			if ($eyes > 0) 
			{
				$ankets->where ('user_eyes', $eyes);
				$oEyes = Eyes::getById ($eyes);
				$critEyes = '<br /> глаза <strong>' . $oEyes->name . '</strong>';
				$crits		= true;
			}

			if ($country > 0) 
			{
				$ankets->where ('user_country', $country);
				$crits		= true;
			}
		  
			if ($region > 0) 
			{
				$ankets->where ('user_region', $region);
				$crits		= true;
			}
		  
			if ($city > 0) 
			{
				$ankets->where ('user_city', $city);
				$crits		= true;
			}

			$ankets = $ankets->orderBy('user_refresh_date_t', 'desc')->paginate($anketPerPage);
			$ankets = LengthPager::makeLengthAware($ankets, $ankets->total(), $anketPerPage);
			$ankets->appends(request()->query());
			$ankets = User::addProps($ankets);

			$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

			$countSearchAnkStr	= Helper::getFoundStr ($ankets, $anketPerPage);

			if ($crits === true) 
			{
				$critsSearch = 'Вы ищете: ';
				$critsSearch .= !empty ($critSex) ? $critSex : '<strong>мужчину</strong> или <strong>женщину</strong>';
				$critsSearch .= !empty ($critAgeMin) ? $critAgeMin : '';
				$critsSearch .= !empty ($critAgeMax) ? $critAgeMax : '';
				if (($ageMin > AGE_MIN) || ($ageMax > AGE_MAX)) 
				{
					$critsSearch .= $ageMax > AGE_MAX ? ' ' . Helper::ageType2($ageMax) : ' ' . Helper::ageType2($ageMin);
				}

				if (!empty($critHeightMin) || !empty($critHeightMax)) 
					$critsSearch .= '<br /> рост ';

				$critsSearch .= !empty($critHeightMin) 	? $critHeightMin . ' см' 	: '';
				$critsSearch .= !empty($critHeightMax) 	? $critHeightMax . ' см' 	: '';

				if ($critWeightMin || $critWeightMax)
					$critsSearch .= '<br />вес ';

				$critsSearch .= !empty($critWeightMin) 	? $critWeightMin . ' кг' 	: '';
				$critsSearch .= !empty($critWeightMax)	? $critWeightMax . ' кг' 	: '';
				$critsSearch .= !empty($critBody)		? $critBody 				: '';
				$critsSearch .= !empty($critHairType)	? $critHairType 			: '';
				$critsSearch .= !empty($critEyes) 		? $critEyes 				: '';

				if ($city > 0) 
				{
					$oCity = $this->cityRepository->getById ($city);
					$critsSearch .= '<br /> из г. <strong>' . $oCity->name . '</strong>';
				} else if ($region > 0) 
				{
					$oRegion = $this->regionRepository->getById ($region);
					$critsSearch .= '<br /> из <strong>' . $oRegion->name . '</strong>';
				}

				if ($country > 0) 
				{
					$oCountry = $this->countryRepository->getById ($country);
					if ($city > 0 || $region > 0)
						$critsSearch .= ' (<strong>' . $oCountry->name . ')</strong>';
					else
						$critsSearch .= '<br /> из <strong>' . $oCountry->name . '</strong>';
				}

				if (!empty($photo))
					$critsSearch .= '<br />только <strong>с фото</strong>';

				if (!empty($online))
					$critsSearch .= '<br />сейчас <strong>на сайте</strong>';
			}
		}
		$ages 		= Helper::getAges();
		$countries 	= $this->countryRepository->getAll();
		$heights 	= Helper::getHeights();
		$weights 	= Helper::getWeights();
		$body 		= Helper::BlockSelect('body',BODY_CLASS,0,0);
		$hairType	= Helper::BlockSelect('hair_type',HAIR_TYPE_CLASS,0,0);
		$eyes		= Helper::BlockSelect('eyes',EYES_CLASS,0,0);

		return response()->view ('ankets.search', 
		[
			'ages'				=> $ages,
			'countries' 		=> $countries,
			'heights'			=> $heights,
			'weights'			=> $weights,
			'body'				=> $body,
			'hairType'			=> $hairType,
			'eyes'				=> $eyes,
			'isSend'			=> $isSend,
			'critsSearch' 		=> $critsSearch,
			'ankets'			=> !empty ($ankets) ? $ankets : [],
			'pagination'		=> !empty ($pagination) ? $pagination : [],
			'photo'				=> !empty ($photo) ? $photo : 0,
			'countSearchAnkStr' => !empty($countSearchAnkStr) ? $countSearchAnkStr : ''
		]);
	}

}