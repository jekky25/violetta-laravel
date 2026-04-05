<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use J25\GoogleCaptcha\GoogleCaptcha;
use Illuminate\Contracts\Validation\Validator;

class ScreenDownloadRequest extends FormRequest
{
	/**
	* replace array errors from default to commit
	* @param  Validator  $validator
	* @return void
	*/
	public function failedValidation(Validator $validator)
	{
		$exception = $validator->getException();
        throw (new $exception($validator))
                    ->errorBag('download')
                    ->redirectTo($this->getRedirectUrl());
	}


	/**
	* messages for the request
	* @return string array
	*/
	public function messages():array
	{
		return	[
			'recaptcha_response.required'		=> 'Капча не пройдена',
			'recaptcha_response.GoogleCaptcha'	=> 'Капча не пройдена',
			'f_download.required'				=> 'Не введен тип закачки',
			'f_download.integer'				=> 'Поле должно быть числом'
		];
	}

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		$rules = ['f_download' => ['required', 'integer']];
		if (config('services.recaptcha.enabled')) $rules['recaptcha_response'] = ['required', new GoogleCaptcha];
		return $rules;
	}
}