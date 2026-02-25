<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;

class ProfilePartnerField extends Field
{
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
		self::$user = $this->user();
		parent::__construct($country, $data, $format);
	}

    public function languages($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPEAK_LANG_CLASS, $this->check($val));
	}

    public function alcohol($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPIRT_CLASS, $this->check($val));
	}

    public function smoke($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SMOKE_CLASS, $this->check($val));
	}

    public function education($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(EDUCATION_CLASS, $this->check($val));
	}
}