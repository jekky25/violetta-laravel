<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckAuthorize;

class ScreenRequest extends FormRequest
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
			'description.required'			=> 'Комментарий не заполнен',
			'description.max'				=> 'Ваш комментарий слишком длинный',
			'description.CheckAuthorize'	=> 'Только авторизованные пользователи могут оставлять комментарии'
		];
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'description'	=> ['required', 'max:1000', new CheckAuthorize],
		];
	}

	/**
	 * Get the validated data from the request.
	 *
	 * @param  array|int|string|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function validated($key = null, $default = null)
	{
		$arParams = $this->validator->validated();
		$user = Auth::user();
		$ar = [
			'scr_id'		=> (int)$this->id,
			'description'	=> str_replace("\'", "''", $arParams['description']),
			'name'			=> $user->name,
			'email'			=> $user->user_mail,
			'create_time'	=> time()
		];
		$ar  = array_merge($arParams, $ar);
		return data_get($ar, $key, $default);
	}
}
