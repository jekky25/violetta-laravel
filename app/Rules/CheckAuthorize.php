<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class CheckAuthorize implements ValidationRule
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
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$user = Auth::user();
		if (empty ($user))
			$fail('Только авторизованные пользователи могут оставлять комментарии');
	}
}