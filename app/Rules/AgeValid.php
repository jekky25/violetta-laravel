<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgeValid implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $ageMin;
	public $ageMax;
	
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(int $ageMin, int $ageMax)
	{
		$this->ageMin		= $ageMin;
		$this->ageMax		= $ageMax;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!($this->ageMin > 15 && 
			$this->ageMax > 15 && 
			$this->ageMin <= $this->ageMax))
				$fail('Не верно указан возраст партнера');
	}
}