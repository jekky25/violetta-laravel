<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BirthDataCorrect implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $birthDay;
	public $birthMonth;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(int $birthDay, int $birthMonth)
	{
		$this->birthDay		= $birthDay;
		$this->birthMonth	= $birthMonth;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ((	$this->birthMonth	== "2" && $this->birthDay 	> "29") || 
			((	$this->birthMonth 	== "4" || $this->birthMonth == "6" || $this->birthMonth == "9" || $this->birthMonth == "11") && $this->birthDay > "30"))
				$fail('Некорректная дата рождения');
	}
}