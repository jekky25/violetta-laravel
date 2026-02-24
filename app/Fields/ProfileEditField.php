<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;
use Illuminate\Support\Facades\Auth;

class ProfileEditField extends Field
{
	public $names = ['day', 'month', 'year', 'country', 'region', 'city'];

	private static $user = null;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected CountryInterface $country,
		protected DataService $data,
		protected FormatService $format,
		protected RegionInterface $region,
		protected CityInterface $city
	)
	{
		if (self::$user === null) self::$user = Auth::user();
		parent::__construct($country, $data, $format);
	}

	public function day() :array
	{
		return $this->data->getDays();
	}

	public function month() :array
	{
		return $this->data->getMonths();
	}

	public function year() :array
	{
		return $this->data->getYears();
	}

	public function region() :\Illuminate\Database\Eloquent\Collection|array
	{
		$userCountryId = self::$user !== null ? self::$user->country_id : null;
		$countryId	= (int) old('country_id', $userCountryId);
		return $countryId > 0 ? $this->region->getByCountryId($countryId) : [];
	}

	public function city() :\Illuminate\Database\Eloquent\Collection|array
	{
		$userRegionId = self::$user !== null ? self::$user->region_id : null;
		$regionId = (int) old('region_id', $userRegionId);
		return $regionId > 0 ? $this->city->getByRegionId($regionId) : [];
	}
}