<?php

namespace App\Http\Controllers;

use App\Services\RegionService;
use App\Http\Resources\Region\RegionResource;

class RegionController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		private RegionService $service
	) {}

	/**
	 * get regions of the country
	 * @param int $countryId
	 * @return \Illuminate\Http\Response
	 */
	public function index(int $countryId)
	{
		$regions = $this->service->getList($countryId);
		return RegionResource::collection($regions);
	}
}