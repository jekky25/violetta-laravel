<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Repositories\UserRepository;

class CheckEmail implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	protected $userRepository;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->userRepository = new UserRepository;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $email, Closure $fail): void
	{
		$isEmail = $this->userRepository->getByEmail($email);
		if (!empty($isEmail))
			$fail('Пользователь с таким Е-майл уже зарегистрирован');
	}
}