<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProfileSecondRequest extends FormRequest
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
		];
	}
}