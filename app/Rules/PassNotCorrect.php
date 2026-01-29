<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class PassNotCorrect implements ValidationRule
{
	/**
	 * Indicates whether the rule should be implicit.
	 *
	 * @var bool
	 */
	public $implicit 			= true;
	public $user 				= null;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->user					= Auth::user();
	}

	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ($value != $this->user->password)
			$fail('Старый пароль указан не верно');
	}
}
