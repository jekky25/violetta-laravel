<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	/**
	* Prepare params for validation
	*
	* @return void
	*/
	protected function prepareForValidation()
    {
        $this->merge([
			'login'		=> !empty($this->login)	? $this->login 	: '',
			'password'	=> !empty($this->password) ? $this->password : ''
        ]);
    }

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		return [
				'login'			=> ['string'],
				'password'		=> ['string']
		];
	}
}