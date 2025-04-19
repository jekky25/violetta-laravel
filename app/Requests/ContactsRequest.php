<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use J25\GoogleCaptcha\GoogleCaptcha;

class ContactsRequest extends FormRequest
{
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
			'name.required'						=> 'Имя не заполнено',
			'name.max'							=> 'Имя слишком длинное',
			'name.min'							=> 'Имя слишком короткое',
			'email.required'					=> 'не указан Е-майл',
			'email.regex'						=> 'Указан некорректный Е-майл',
			'recaptcha_response.required'		=> 'Капча не пройдена',
			'recaptcha_response.GoogleCaptcha'	=> 'Капча не пройдена'
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
			'name'					=> ['required', 'max:30', 'min:2'],
			'email'					=> ['required', "regex:/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}|museum$/i"],
			'organization'			=> ['string'],
			'description'			=> ['string'],
			'recaptcha_response'	=> ['required', new GoogleCaptcha]
		];
	}
}
