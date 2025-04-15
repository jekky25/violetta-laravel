<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SettingRequest extends FormRequest
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
	 * Prepare params for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'dont_send_email' 		=> !empty($this->dont_send_email) ? (int) $this->dont_send_email : 0,
			'refresh_date'			=> date("Y-m-d"),
			'refresh_date_t'		=> time(),
			'session_time'			=> time(),
			'lastvisit'				=> time()
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
			'dont_send_email'			=> ['integer'],
			'refresh_date'				=> ['string'],
			'refresh_date_t'			=> ['integer'],
			'session_time'				=> ['integer'],
			'lastvisit'					=> ['integer']
		];
	}
}
