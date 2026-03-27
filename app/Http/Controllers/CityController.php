<?php

namespace App\Http\Controllers;

use App\Services\CityService;
use App\Http\Resources\City\CityShortResource;

class CityController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		private CityService $service
	) {}

	/**
	 * get cities of the region
	 * @param int $regionId
	 * @return \Illuminate\Http\Response
	 */
	public function index(int $regionId)
	{
		$cities = $this->service->getList($regionId);
		return CityShortResource::collection($cities);
	}
}