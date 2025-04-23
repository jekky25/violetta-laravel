<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Route;
use App\Rules\CheckOften;

class PrivmsgRequest extends FormRequest
{
	private $routeMessageDelete 		= 'privmsg.post.delete.action';
	private $routeUserMessagesDelete 	= 'privmsg.delete';


	/**
	 * replace array errors from default to commit
	 * @param  Illuminate\Contracts\Validation\Validator  $validator
	 * @return void
	 */
	public function failedValidation(Validator $validator)
	{
		$exception = $validator->getException();
		$this->errorBag = 'comment';
		throw (new $exception($validator))
			->errorBag($this->errorBag)
			->redirectTo($this->getRedirectUrl());
	}

	/**
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'message_text.required' 	=> 'Сообщение не заполнено',
			'message_text.max'	 		=> 'Сообщение слишком длинное',
			'message_text.min'	 		=> 'Сообщение слишком короткое',
			'check_often'				=> 'Вы превысили лимит отправляемых сообщений:<br /> не более 10 сообщений за 5 минут'
		];
	}

	/**
	 * rules for the request
	 * @return string array
	 */
	public function rules(): array
	{
		if ($this->cancelRules()) return [];
		return [
			'message_text'	=> ['required', 'max:3000', 'min:2', new CheckOften()]
		];
	}

	/**
	 * check routes for cancel
	 * @return bool
	 */
	private function cancelRules()
	{
		if (Route::currentRouteName() == $this->routeMessageDelete) return true;
		if (Route::currentRouteName() == $this->routeUserMessagesDelete) return true;
		return false;
	}
}
