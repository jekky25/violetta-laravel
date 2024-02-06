<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Helper;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;

class RegistrationController extends Controller
{


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
		return redirect()->back()
						->withInput();
	}
}