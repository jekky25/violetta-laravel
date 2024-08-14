<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CityInterface;
use App\Interfaces\RegionInterface;
use App\Requests\GeoRequest;
use App\Helpers\Helper;

class AjaxController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected CityInterface $cityRepository,
		protected RegionInterface $regionRepository
	)
	{
	}

	/**
	* get city or region
	* @param  GeoRequest  $request
	* @return bool
	*/
	public function getGeo (GeoRequest $request)
	{
		$selectid = $request->get('selectid');
		switch ($selectid) 
		{
			case 'region':
				self::getGeoRegion($request);
				break;
			case 'city':
				self::getGeoCity($request);
				break;
		}
		return false;
	}

	/**
	* get region
	* @param  GeoRequest  $request
	* @return void
	*/
	public function getGeoRegion (GeoRequest $request)
	{
		$id = (int)$request->get('id');
		if ($id == 0) return false;
		$regions = $this->regionRepository->getByCountryId($id);
		Helper::outToXml($regions);
	}

	/**
	* get city
	* @param  GeoRequest  $request
	* @return void
	*/
	public function getGeoCity (GeoRequest $request)
	{
		$id = (int)$request->get('id');
		if ($id == 0) return false;

		$cities = $this->cityRepository->getByRegionId($id);
		Helper::outToXml($cities);
	}
}
