<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\City;
use App\Helpers\Helper;

class AjaxController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
	}

	/**
	* get city or region
	* @param  \Illuminate\Http\Request  $request
	* @return bool
	*/
	public function getGeo (Request $request)
	{
		$selectid = $request->get('selectid');
		switch ($selectid) 
		{
			case 'region':
				AjaxController::getGeoRegion($request);
				break;
			case 'city':
				AjaxController::getGeoCity($request);
				break;
		}
		return false;
	}

	/**
	* get region
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function getGeoRegion (Request $request)
	{
		$id = (int)$request->get('id');
		if ($id == 0) return false;
		$regions = Region::getByCountryId($id);
		Helper::outToXml($regions);
	}

	/**
	* get city
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function getGeoCity (Request $request)
	{
		$id = (int)$request->get('id');
		if ($id == 0) return false;

		$cities = City::getByRegionId($id);
		Helper::outToXml($cities);
	}
}
