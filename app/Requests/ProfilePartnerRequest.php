<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Services\DataService;
use App\Rules\AgeValid;
use App\Rules\HeightValid;
use App\Rules\WeightValid;

class ProfilePartnerRequest extends FormRequest
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
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'AgeValid'		=> 'Не верно указан возраст партнера',
			'WeightValid' 	=> 'Не верно указан вес партнера',
			'HeightValid' 	=> 'Не верно указан рост партнера'
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
			'age' 		=> [
				new AgeValid($this->partner_age_min, $this->partner_age_max),
				new WeightValid($this->partner_weight_min, $this->partner_weight_max),
				new HeightValid($this->partner_height_min, $this->partner_height_max)
			],
			'partner_age_min'			 => ['integer'],
			'partner_age_max'			=> ['integer'],
			'partner_height_min'		=> ['integer'],
			'partner_height_max'		=> ['integer'],
			'partner_weight_min'	 	=> ['integer'],
			'partner_weight_max'	 	=> ['integer'],
			'partner_body'				=> ['array'],
			'partner_languages'			=> ['array'],
			'partner_alcohol'			=> ['array'],
			'partner_smoke'				=> ['array'],
			'partner_education'			=> ['array'],
			'partner_country'	 		=> ['integer'],
			'partner_region'		 	=> ['integer'],
			'partner_city'		 		=> ['integer'],
			'partner_description'		=> ['string'],
			'refresh_date'				=> ['string'],
			'refresh_date_t'			=> ['integer'],
			'session_time'				=> ['integer'],
			'lastvisit'					=> ['integer'],
			'approved'					=> ['integer']
		];
	}
}
