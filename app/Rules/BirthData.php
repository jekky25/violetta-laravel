<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BirthData implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $birthDay;
	public $birthMonth;
	public $birthYear;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(int $birthDay, int $birthMonth, int $birthYear)
	{
		$this->birthDay		= $birthDay;
		$this->birthMonth	= $birthMonth;
		$this->birthYear	= $birthYear;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ($this->birthDay == 0 		|| 
			$this->birthMonth == 0 		|| 
			$this->birthYear == 1900	|| 
			$this->birthYear == 0)
			$fail('Не указана дата рождения');
	}
}