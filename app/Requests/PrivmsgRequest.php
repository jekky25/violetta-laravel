<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Route;
use App\Rules\CheckOften;

class PrivmsgRequest extends FormRequest
{
	private $routeMessageDelete 		= 'privmsg.post.delete';
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
	public function messages():array
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
	public function rules():array
	{
		if ($this->cancelRules()) return [];
		return [
			'message_text'	=> ['required', 'max:3000', 'min:2', new CheckOften()]
		];
	}


	/**
	* Get the validated data from the request.
	*
	* @param  array|int|string|null  $key
	* @param  mixed  $default
	* @param  integer $id
	* @param  integer $user_id
	* @return mixed
	*/
	public function validated($key = null, $default = null, $id = 0, $user_id = 0)
    {
		$arParams						= $this->validator->validated();
		$arParams['user_otprav']		= $user_id;
		$arParams['user_poluchil']		= $id;
		$arParams['user_otprav_del']	= 0;
		$arParams['user_poluchil_del']	= 0;
		$arParams['time']				= time();
		$arParams['mess_new']			= 1;
		$arParams['privmess_text']		= str_replace("\'", "''", $arParams['message_text']);
		return data_get($arParams, $key, $default);
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