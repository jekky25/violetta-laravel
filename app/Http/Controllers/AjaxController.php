<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
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
        // $this->middleware('auth');
    }


	public function getGeo (Request $request)
	{

		$req = $request->post();
		switch ($req['selectid']) 
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

	public function getGeoRegion (Request $request)
	{
		$req = $request->post();
		$id = !empty ($req['id']) ? (int)$req['id'] : 0;
		if ($id == 0) return false;

		$regions = Region::getByCountryId($id);
		Helper::outToXml($regions);
	}

	public function getGeoCity (Request $request)
	{
		$req = $request->post();
		$id = !empty ($req['id']) ? (int)$req['id'] : 0;
		if ($id == 0) return false;

		$cities = City::getByRegionId($id);
		Helper::outToXml($cities);
	}
}
