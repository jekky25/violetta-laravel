<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Repositories\UserRepository;

class CheckLogin implements ValidationRule
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
	public function validate(string $attribute, mixed $login, Closure $fail): void
	{
		$isLogin = $this->userRepository->getByLogin($login);
		if (!empty($isLogin))
			$fail('Пользователь с таким логином уже существует, выберите другой логин');
	}
}