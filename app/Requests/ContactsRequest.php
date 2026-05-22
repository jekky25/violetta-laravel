<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use J25\GoogleCaptcha\GoogleCaptcha;

class ContactsRequest extends FormRequest
{
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
			'email.email'						=> 'Указан некорректный Е-майл',
			'recaptcha_response.required'		=> 'Капча не пройдена',
			'recaptcha_response.GoogleCaptcha'	=> 'Капча не пройдена',
			'organization.string'				=> 'Поле: организация должно быть строкой',
			'description.string'				=> 'Поле: описание должно быть строкой'
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
			'email'					=> ['required', 'email'],
			'organization'			=> ['string'],
			'description'			=> ['string'],
			'recaptcha_response'	=> ['required', new GoogleCaptcha]
		];
	}
}
