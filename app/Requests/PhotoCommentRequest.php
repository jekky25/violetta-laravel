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
}