<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlaceCorrect implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $city;
	public $region;
	public $country;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(int $city, int $region, int $country)
	{
		$this->city		= $city;
		$this->region	= $region;
		$this->country	= $country;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (	!$this->allEmpty($this->city, $this->region, $this->country) &&
				$this->oneEmpty($this->city, $this->region, $this->country))
		{
			$fail('Неверно указано место жительства');
		}
	}

	/**
	* Check if all params are empty
	*
	* @param  int $city
	* @param  int $region
	* @param  int $country
	*/
	public function allEmpty ($city, $region, $country): bool
	{
		return $city == 0 && $region == 0 && $country == 0 ? true : false;
	}

	/**
	* Check if one param is empty
	*
	* @param  int $city
	* @param  int $region
	* @param  int $country
	*/
	public function oneEmpty ($city, $region, $country): bool
	{
		return $city == 0 || $region == 0 || $country == 0 ? true : false;
	}
}