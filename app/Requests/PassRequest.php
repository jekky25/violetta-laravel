<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\PassNotCorrect;
use App\Rules\PassNotMatch;

class PassRequest extends FormRequest
{
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
			'pass_old.required'		=> 'Старый пароль не заполнен',
			'PassNotCorrect'		=> 'Старый пароль указан не верно',
			'PassNotMatch'			=> 'Новые пароли не совпадают',
			'pass.required'			=> 'Новый пароль не заполнен',
			'pass.max'		 		=> 'Новый пароль слишком длинный',
			'pass.min'		 		=> 'Новый пароль слишком короткий',
		];
	}

	/**
	 * Prepare params for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'user_password'			=> $this->pass,
			'user_hash'				=> md5($this->pass),
			'session_time'			=> time(),
			'lastvisit'				=> time()
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$arParams = $this->post();
		return [
			'pass_old'					=> ['required', new PassNotCorrect],
			'pass'						=> ['required', 'max:15', 'min:5', new PassNotMatch((string)$arParams['pass'], (string)$arParams['pass_confirm'])],
			'user_password'				=> ['string'],
			'user_hash'					=> ['string'],
			'session_time'				=> ['integer'],
			'lastvisit'					=> ['integer']
		];
	}
}
