<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\AnketVisit;
use App\Models\User;
use App\Models\Country;
use App\Helpers\Helper;

class AnketController extends Controller
{
	public $countPerPage 	= 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
    }

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


	public function getBirthdayAnkets ()
	{
		$ankets 			= User::getBirthday($this->countPerPage);

		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		return response()->view ('ankets.birthday_search', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

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

		$ankets 			= User::getOp($this->countPerPage, $s, $opt);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		$startShowAnk 		= (($ankets->currentPage() - 1) * $this->countPerPage) + 1;
		$endShowAnk			= $ankets->currentPage() * $this->countPerPage;

		$countSearchAnkStr = 'Найдено анкет: (' . $startShowAnk . '-' . $endShowAnk . ') из ' . $ankets->total();

		return response()->view ('ankets.id', 
		[
			'popSex'			=> $ankTitle,
			'ankTitleId'		=> $ankTitleId,
			'sex'				=> $sex,
			'page'				=> $page,
			'ankets'			=> $ankets,
			'countSearchAnkStr' => $countSearchAnkStr,
			'pagination'		=> $pagination
		]);
	}
	

	public function getBySearch(Request $request)
	{
		$ages 		= Helper::getAges();
		$countries 	= Country::getAll();
		$heights 	= Helper::getHeights();
		$weights 	= Helper::getWeights();
		$body 		= Helper::BlockSelect('body',BODY_CLASS,0,0);
		$hairType	= Helper::BlockSelect('hair_type',HAIR_TYPE_CLASS,0,0);
		$eyes		= Helper::BlockSelect('eyes',EYES_CLASS,0,0);
		
		return response()->view ('ankets.search', 
		[
			'ages'		=> $ages,
			'countries' => $countries,
			'heights'	=> $heights,
			'weights'	=> $weights,
			'body'		=> $body,
			'hairType'	=> $hairType,
			'eyes'		=> $eyes,
		]);
	}

}