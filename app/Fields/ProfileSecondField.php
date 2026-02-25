<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;

class ProfileSecondField extends Field
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

	public function sexOrient($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SEX_ORIENT_CLASS, $this->check($val));
	}

	public function hairColor($val) :\Illuminate\Database\Eloquent\Collection
	{
		return	$this->format->BlockSelect(HAIR_COLOR_CLASS, $this->check($val));
	}

	public function education($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(EDUCATION_CLASS, $this->check($val));
	}

	public function smoke($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SMOKE_CLASS, $this->check($val));
	}

	public function alcohol($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPIRT_CLASS, $this->check($val));
	}

	public function familyStatus($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(FAMILY_STATUS_CLASS, $this->check($val));
	}

	public function children($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(CHILDREN_CLASS, $this->check($val));
	}

	public function helpMoney($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(HELP_MONEY_CLASS, $this->check($val));
	}

	public function interests($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(INTEREST_CLASS, $this->check($val));
	}

	public function targets($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(MEET_TARGET_CLASS, $this->check($val));
	}

	public function languages($val) :\Illuminate\Database\Eloquent\Collection
	{
		return $this->format->BlockSelect(SPEAK_LANG_CLASS, $this->check($val));
	}
}