<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\AnketVisit;
use App\Models\User;
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

		foreach ($ankets as &$item)
		{
			$findSOrient = '';
			if ($item->user_sex_orient == GOMOSEXUAL) 
				$findSOrient .= $item->user_sex == MEN ? 'парня' : 'девушку';
			elseif ($item->user_sex_orient == BISEXUAL) 
				$findSOrient .= $item->user_sex == MEN ? 'девушку или парня' : 'парня или девушку';
			else
				$findSOrient .= $item->user_sex == MEN ? 'девушку' : 'парня';

			if ($item->user_partner_age_min > PARTNER_AGE_MIN && $item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' ' . $item->user_partner_age_min . '-' . $item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType($item->user_partner_age_max);
			} elseif ($item->user_partner_age_min > PARTNER_AGE_MIN && $item->user_partner_age_max <= PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' от ' . $item->user_partner_age_min;
				$findSOrient .= ' ' . Helper::ageType2($item->user_partner_age_min);
			} elseif ($item->user_partner_age_min <= PARTNER_AGE_MIN && $item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' до ' . $item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType2($item->user_partner_age_max);
			}
			$item->find_sex_orient = $findSOrient;

		}
		
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
		$ankets 	= User::getBirthday($this->countPerPage);

		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		foreach ($ankets as &$item)
		{
			$findSOrient = '';
			if ($item->user_sex_orient == GOMOSEXUAL) 
				$findSOrient .= $item->user_sex == MEN ? 'парня' : 'девушку';
			elseif ($item->user_sex_orient == BISEXUAL) 
				$findSOrient .= $item->user_sex == MEN ? 'девушку или парня' : 'парня или девушку';
			else
				$findSOrient .= $item->user_sex == MEN ? 'девушку' : 'парня';

			if ($item->user_partner_age_min > PARTNER_AGE_MIN && $item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' ' . $item->user_partner_age_min . '-' . $item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType($item->user_partner_age_max);
			} elseif ($item->user_partner_age_min > PARTNER_AGE_MIN && $item->user_partner_age_max <= PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' от ' . $item->user_partner_age_min;
				$findSOrient .= ' ' . Helper::ageType2($item->user_partner_age_min);
			} elseif ($item->user_partner_age_min <= PARTNER_AGE_MIN && $item->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				$findSOrient .= ' до ' . $item->user_partner_age_max;
				$findSOrient .= ' ' . Helper::ageType2($item->user_partner_age_max);
			}
			$item->find_sex_orient = $findSOrient;

		}

		return response()->view ('ankets.birthday_search', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

}