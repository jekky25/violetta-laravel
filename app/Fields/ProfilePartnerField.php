<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;
use Illuminate\Support\Facades\Auth;

class ProfilePartnerField extends Field
{
	public $names = ['age', 'height', 'weight', 'partnerBody', 'partnerLanguages', 'partnerAlcohol', 'partnerSmoke', 'partnerEducation', 'country', 'partnerRegion', 'partnerCity'];

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

	public function partnerBody() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(BODY_CLASS, self::$user !== null ? self::$user->partner_body : 0);
	}

	public function partnerLanguages() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPEAK_LANG_CLASS, self::$user !== null ? self::$user->partner_languages : 0);
	}

	public function partnerAlcohol() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPIRT_CLASS, self::$user !== null ? self::$user->partner_alcohol : 0);
	}

	public function partnerSmoke() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SMOKE_CLASS, self::$user !== null ? self::$user->partner_smoke : 0);
	}

	public function partnerEducation() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(EDUCATION_CLASS, self::$user !== null ? self::$user->partner_education : 0);
	}

	public function partnerRegion() :\Illuminate\Database\Eloquent\Collection|array
	{
		$userCountryId = self::$user !== null ? self::$user->partner_country : null;
		$countryId	= (int) old('partner_country_id', $userCountryId);
		return $countryId > 0 ? $this->region->getByCountryId($countryId) : [];
	}

    public function partnerCity() :\Illuminate\Database\Eloquent\Collection|array
	{
		$userRegionId = self::$user !== null ? self::$user->partner_region : null;
		$regionId = (int) old('partner_region_id', $userRegionId);
		return $regionId > 0 ? $this->city->getByRegionId($regionId) : [];
	}
}