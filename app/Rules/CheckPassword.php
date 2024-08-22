<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPassword implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $password;
	public $passwordSecond;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(string $password, string $passwordSecond)
	{
		$this->password 		= $password;
		$this->passwordSecond 	= $passwordSecond;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $login, Closure $fail): void
	{
		if ($this->password != $this->passwordSecond)
			$fail('Введенные пароли не совпадают');
	}
}