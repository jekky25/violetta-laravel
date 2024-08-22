<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Repositories\BanListRepository;

class CheckBan implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	protected $banListRepository;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->banListRepository = new BanListRepository;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$ip 					= \Request::ip();
		$ban					= $this->banListRepository->getByIP($ip);
		if (!empty($ban))
			$fail('Вы забанены из-за нарушения правил нашего сайта, по всем вопросам обращайтесь к администрации сайта');
	}
}