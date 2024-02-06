<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use App\Helpers\Helper;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;

class RegistrationController extends Controller
{

	public static $rulesEdit = [
		'name'	=> ['required', 'max:30', 'min:2', 'birth_data', 'birth_data_correct'],
		'sex'	=> ['required'],
		'city'	=> ['place_empty', 'place_correct'],
	];

	public static $errMessagesEdit = [
		'name.required'		 	=> 'Имя не заполнено',
		'name.max'		 		=> 'Имя слишком длинное',
		'name.min'		 		=> 'Имя слишком короткое',
		'sex.required'		 	=> 'Вы не указали пол',
		'birth_data'			=> 'Не указана дата рождения',
		'birth_data_correct'	=> 'Некорректная дата рождения',
		'place_empty'			=> 'Не указано место жительства',
		'place_correct'			=> 'Неверно указано место жительства'
	];

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
    }

	public function edit (Request $request)
	{
		$user 		= Auth::user();
		$days 		= Helper::getDays();
		$months 	= Helper::getMonths();
		$years 		= Helper::getYears();
		$countries	= Country::getAll();
		$countryId	= (int) old ('country', $user->user_country);
		$regionId	= (int) old ('region', $user->user_region);
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? City::getByRegionId($regionId) 		: [];

		return response()->view ('registration.edit',
		[
			'userData'		=> $user,
			'days'			=> $days,
			'months'		=> $months,
			'years'			=> $years,
			'countries'		=> $countries,
			'regions'		=> $regions,
			'cities'		=> $cities,
		]);
	}

	public function editPost (Request $request)
	{
		$arParams 		= $request->post();

		Validator::extend('birth_data',
		function () use ($arParams) {
			return ((int)$arParams['birth_day'] == 0 || (int)$arParams['birth_month'] == 0 || (int)$arParams['birth_year'] == 1900 || (int)$arParams['birth_year'] == 0) ? false : true;
		});

		Validator::extend('birth_data_correct',
		function () use ($arParams) {
			$birth_month 	= (int)$arParams['birth_month'];
			$birth_day		= (int)$arParams['birth_day'];
			return (($birth_month == "2" && $birth_day > "29") || (($birth_month == "4" || $birth_month == "6" || $birth_month == "9" || $birth_month == "11") && $birth_day > "30")) ? false : true;
		});

		Validator::extend('place_empty',
		function () use ($arParams) {
			return (int)$arParams['city'] == 0 && (int)$arParams['region'] == 0 && (int)$arParams['country'] == 0 ? false : true;
		});

		Validator::extend('place_correct',
		function () use ($arParams) {
			if ((int)$arParams['city'] == 0 && (int)$arParams['region'] == 0 && (int)$arParams['country'] == 0) return true;
			return (int)$arParams['city'] == 0 || (int)$arParams['region'] == 0 || (int)$arParams['country'] == 0 ? false : true;
		});

		$validator 		= Validator::make($arParams, self::$rulesEdit, self::$errMessagesEdit);

		if ($validator->fails()) {

			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
				->withErrors($strError, 'comment')
				->withInput();
		}




		return redirect()->back()
		->withInput();


	}
}