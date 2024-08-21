<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PhotoRequest extends FormRequest
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
			'photo_link.image'		=> 'Файл не является изображением',
			'photo_link.max'		=> 'Файл слишком большой',
			'photo_link.required'	=> 'Файл не был загружен'
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
			'photo_link'	=> ['required','file', 'image', 'max:4048']
		];
	}
}