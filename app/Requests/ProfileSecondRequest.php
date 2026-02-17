<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Services\DataService;

class ProfileSecondRequest extends FormRequest
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(DataService $data)
	{
		$this->data = $data;
	}

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
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'sex_orient'	 		=> ['integer'],
			'targets'				=> ['array'],
			'speak_lang'	 		=> ['nullable', 'array'],
			'body'		 			=> ['integer'],
			'height'	 			=> ['integer'],
			'weight' 				=> ['integer'],
			'hair_color'			=> ['integer'],
			'hair_type'				=> ['integer'],
			'eyes'					=> ['integer'],
			'education'				=> ['integer'],
			'smoke'					=> ['integer'],
			'alcohol'				=> ['integer'],
			'family_status'			=> ['integer'],
			'children'				=> ['integer'],
			'help_money'			=> ['integer'],
			'interests'				=> ['array'],
			'icq'					=> ['nullable', 'string'],
			'url'					=> ['nullable', 'string'],
			'phone'					=> ['nullable', 'string'],
			'description'			=> ['string'],
			'refresh_date'			=> ['string'],
			'refresh_date_t'		=> ['integer'],
			'session_time'			=> ['integer'],
			'lastvisit'				=> ['integer'],
			'approved'				=> ['integer']
		];
	}
}
