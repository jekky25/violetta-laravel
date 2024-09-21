<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PhotoCommentRequest extends FormRequest
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
	public function messages():array
	{
		return	[
					'description.required' 	=> 'Комментарий не заполнен',
					'description.max'	 	=> 'Ваш комментарий слишком длинный',
					'description.min'	 	=> 'Ваш комментарий слишком короткий',
				];
	}

	/**
	* rules for the request
	* @return string array
	*/
	public function rules():array
	{
		return [
			'description'	=> ['required', 'max:1000', 'min:2']
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
	public function validated($key = null, $default = null, $foto_id = 0, $user_id = 0)
    {
		$arParams = $this->validator->validated();
		$arParams['foto_id']				= $foto_id;
		$arParams['user_id']				= $user_id;
		$arParams['comments_description']	= str_replace("\'", "''", $arParams['description']);
		$arParams['time']					= time();
		return data_get($arParams, $key, $default);
	}
}