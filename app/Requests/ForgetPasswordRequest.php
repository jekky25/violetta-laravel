<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
	/**
	* messages for the request
	* @return string array
	*/
	public function messages():array
	{
		return	[
			'email.required'			=> 'не указан Е-майл',
			'email.email'			=> 'указан некорректный Е-майл'
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
			'email'		=> ['required', 'email']
		];
	}
}