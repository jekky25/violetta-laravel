<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HeightValid implements ValidationRule
{
	/**
	 * Indicates whether the rule should be implicit.
	 *
	 * @var bool
	 */
	public $implicit 			= true;
	public $heightMin;
	public $heightMax;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(int $heightMin, int $heightMax)
	{
		$this->heightMin		= $heightMin;
		$this->heightMax		= $heightMax;
	}

	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (
			$this->heightMax > User::HEIGHT_MIN &&
			$this->heightMin > $this->heightMax
		)
			$fail('Не верно указан рост партнера');
	}
}
