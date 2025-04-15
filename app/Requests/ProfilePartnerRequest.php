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
	 * Prepare params for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'partner_age_min' 				=> $this->partner_age_min,
			'partner_age_max' 				=> $this->partner_age_max,
			'partner_height_min' 			=> $this->partner_height_min,
			'partner_height_max' 			=> $this->partner_height_max,
			'partner_weight_min' 			=> $this->partner_weight_min,
			'partner_weight_max' 			=> $this->partner_weight_max,
			'partner_body'					=> $this->data->serializeInput($this->partner_body),
			'partner_languages'				=> $this->data->serializeInput($this->partner_languages),
			'partner_alcohol'				=> $this->data->serializeInput($this->partner_alcohol),
			'partner_smoke'					=> $this->data->serializeInput($this->partner_smoke),
			'partner_education'				=> $this->data->serializeInput($this->partner_education),
			'partner_country'	 			=> $this->country,
			'partner_region'		 		=> $this->region,
			'partner_city'	 				=> $this->city,
			'partner_description'			=> addslashes($this->description),
			'user_refresh_date'		=> date("Y-m-d"),
			'user_refresh_date_t'	=> time(),
			'session_time'			=> time(),
			'lastvisit'				=> time()
		]);

		if (!empty($this->partner_description))
			$this->merge([
				'approved'			=> 0
			]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$ageMin 	= !empty($this->partner_age_min) 		? (int)$this->partner_age_min 	: 0;
		$ageMax 	= !empty($this->partner_age_max) 		? (int)$this->partner_age_max 	: 0;
		$heightMin 	= !empty($this->partner_height_min) 	? (int)$this->partner_height_min 	: 0;
		$heightMax 	= !empty($this->partner_height_max) 	? (int)$this->partner_height_max 	: 0;
		$weightMin 	= !empty($this->partner_weight_min) 	? (int)$this->partner_weight_min 	: 0;
		$weightMax 	= !empty($this->partner_weight_max) 	? (int)$this->partner_weight_max 	: 0;
		return [
			'age' 		=> [
				new AgeValid($ageMin, $ageMax),
				new WeightValid($weightMin, $weightMax),
				new HeightValid($heightMin, $heightMax)
			],
			'partner_age_min'			 => ['integer'],
			'partner_age_max'			=> ['integer'],
			'partner_height_min'		=> ['integer'],
			'partner_height_max'		=> ['integer'],
			'partner_weight_min'	 	=> ['integer'],
			'partner_weight_max'	 	=> ['integer'],
			'partner_body'				=> ['string'],
			'partner_languages'			=> ['string'],
			'partner_alcohol'			=> ['string'],
			'partner_smoke'				=> ['string'],
			'partner_education'			=> ['string'],
			'partner_country'	 		=> ['integer'],
			'partner_region'		 	=> ['integer'],
			'partner_city'		 		=> ['integer'],
			'partner_description'		=> ['string'],
			'user_refresh_date'			=> ['string'],
			'user_refresh_date_t'		=> ['integer'],
			'session_time'				=> ['integer'],
			'lastvisit'					=> ['integer'],
			'approved'					=> ['integer']
		];
	}
}
