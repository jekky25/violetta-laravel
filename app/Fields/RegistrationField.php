<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;
use Illuminate\Support\Facades\Auth;

class RegistrationField extends Field
{
	public $names = ['day', 'month', 'year', 'country', 'region', 'city', 'sexOrient', 'body', 'height', 'weight', 
					'hairColor', 'hairType', 'eyes', 'education', 'smoke', 'alcohol', 'familyStatus', 'children', 'helpMoney', 'interests', 'age', 'targets', 'userSpeakLang'];
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

	public function sexOrient() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SEX_ORIENT_CLASS, self::$user !== null ? self::$user->sex_orient : 0);
	}

	public function body() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(BODY_CLASS, self::$user !== null ? self::$user->body : 0);
	}

	public function hairColor() :\Illuminate\Database\Eloquent\Collection
	{
		return	$this->format->BlockSelect(HAIR_COLOR_CLASS, self::$user !== null ? self::$user->hair_color : 0);
	}

	public function hairType() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(HAIR_TYPE_CLASS, self::$user !== null ? self::$user->hair_type : 0);
	}

	public function eyes() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(EYES_CLASS, self::$user !== null ? self::$user->eyes : 0);
	}

	public function education() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(EDUCATION_CLASS, self::$user !== null ? self::$user->education : 0);
	}

	public function smoke() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SMOKE_CLASS, self::$user !== null ? self::$user->smoke : 0);
	}

	public function alcohol() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPIRT_CLASS, self::$user !== null ? self::$user->alcohol : 0);
	}

	public function familyStatus() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(FAMILY_STATUS_CLASS, self::$user !== null ? self::$user->family_status : 0);
	}

	public function children() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(CHILDREN_CLASS, self::$user !== null ? self::$user->children : 0);
	}

	public function helpMoney() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(HELP_MONEY_CLASS, self::$user !== null ? self::$user->help_money : 0);
	}

	public function interests() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(INTEREST_CLASS, self::$user !== null ? self::$user->interests : 0);
	}

	public function targets() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(MEET_TARGET_CLASS, self::$user !== null ? self::$user->targets : 0);
	}

	public function userSpeakLang() :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPEAK_LANG_CLASS, self::$user !== null ? self::$user->speak_lang : 0);
	}
}