<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PassNotMatch implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $pass;
	public $passConfirm;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(string $pass, string $passConfirm
	)
	{
		$this->pass 		= $pass;
		$this->passConfirm 	= $passConfirm;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ($this->pass != $this->passConfirm) 
			$fail('Новые пароли не совпадают');
	}
}