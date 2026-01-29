<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WeightValid implements ValidationRule
{
	/**
	 * Indicates whether the rule should be implicit.
	 *
	 * @var bool
	 */
	public $implicit 			= true;
	public $weightMin;
	public $weightMax;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(int $weightMin, int $weightMax)
	{
		$this->weightMin		= $weightMin;
		$this->weightMax		= $weightMax;
	}

	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (
			$this->weightMax > User::WEIGHT_MIN &&
			$this->weightMin > $this->weightMax
		)
			$fail('Не верно указан рост партнера');
	}
}
