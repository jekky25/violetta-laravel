<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlaceEmpty implements ValidationRule
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
		if ($this->city 	== 0 && 
			$this->region 	== 0 && 
			$this->country	== 0)
				$fail('Не указано место жительства');
	}
}