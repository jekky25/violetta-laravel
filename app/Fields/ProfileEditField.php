<?php
namespace App\Fields;

use App\Interfaces\RegionInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Services\FormatService;
use App\Services\DataService;

class ProfileEditField extends Field
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
}