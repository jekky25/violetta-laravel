<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\AgeValid;
use App\Rules\HeightValid;
use App\Rules\WeightValid;
use App\Helpers\Helper;

class ProfilePartnerRequest extends FormRequest
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
			'user_partner_age_min' 			=> $this->partner_age_min,
			'user_partner_age_max' 			=> $this->partner_age_max,
			'user_partner_height_min' 		=> $this->partner_height_min,
			'user_partner_height_max' 		=> $this->partner_height_max,
			'user_partner_weight_min' 		=> $this->partner_weight_min,
			'user_partner_weight_max' 		=> $this->partner_weight_max,
			'user_partner_body'				=> Helper::serializeInput($this->partner_body),
			'user_partner_speak_lang'		=> Helper::serializeInput($this->partner_speak_lang),
			'user_partner_spirt'			=> Helper::serializeInput($this->partner_spirt),
			'user_partner_smoke'			=> Helper::serializeInput($this->partner_smoke),
			'user_partner_education'		=> Helper::serializeInput($this->partner_education),
			'user_partner_country' 			=> $this->country,
			'user_partner_region'	 		=> $this->region,
			'user_partner_city'	 			=> $this->city,
			'user_partner_description'		=> addslashes($this->description),
			'user_refresh_date'		=> date("Y-m-d"),
			'user_refresh_date_t'	=> time(),
			'user_session_time'		=> time(),
			'user_lastvisit'		=> time()
        ]);

		if (!empty($this->user_partner_description)) 
		$this->merge([
			'user_odobreno'			=> 0
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
				'user_partner_age_min' => ['integer'],
				'user_partner_age_max' => ['integer'],
				'user_partner_height_min'	=> ['integer'],
				'user_partner_height_max'	=> ['integer'],
				'user_partner_weight_min' 	=> ['integer'],
				'user_partner_weight_max' 	=> ['integer'],
				'partner_body'				=> ['array'],
				'user_partner_body'			=> ['string'],
				'partner_speak_lang'		=> ['array'],
				'user_partner_speak_lang'	=> ['string'],
				'partner_spirt'				=> ['array'],
				'user_partner_spirt'		=> ['string'],
				'partner_smoke'				=> ['array'],
				'user_partner_smoke'		=> ['string'],
				'partner_education'			=> ['array'],
				'user_partner_education'	=> ['string'],
				'user_partner_country' 		=> ['integer'],
				'user_partner_region'	 	=> ['integer'],
				'user_partner_city'	 		=> ['integer'],
				'user_partner_description'	=> ['string'],
				'user_refresh_date'		=> ['string'],
				'user_refresh_date_t'	=> ['integer'],
				'user_session_time'		=> ['integer'],
				'user_lastvisit'		=> ['integer'],
				'user_odobreno'			=> ['integer']
		];
	}
}