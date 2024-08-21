<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;

class CheckOften implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit 			= true;
	public $user 				= null;
	public $messageSendLimit 	= 10;
	protected $messageRepository;

		/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
	)
	{
		$this->user					= Auth::user();
		$this->messageRepository 	= new MessageRepository;
	}

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $recaptcha_response, Closure $fail): void
	{
		if (empty($this->user)) $fail('Ошибка авторизации');
		else {
			if ($this->messageRepository->getByTimeByUser($this->user->user_id)->count() > $this->messageSendLimit) 
				$fail('Вы превысили лимит отправляемых сообщений:<br /> не более 10 сообщений за 5 минут');
		}
	}
}