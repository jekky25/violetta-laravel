<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\AgeValid;
use App\Rules\HeightValid;
use App\Rules\WeightValid;

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
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		$arParams 	= $this->post();
		$ageMin 	= !empty($arParams['partner_age_min']) 		? (int)$arParams['partner_age_min'] 	: 0;
		$ageMax 	= !empty($arParams['partner_age_max']) 		? (int)$arParams['partner_age_max'] 	: 0;
		$heightMin 	= !empty($arParams['partner_height_min']) 	? (int)$arParams['partner_height_min'] 	: 0;
		$heightMax 	= !empty($arParams['partner_height_max']) 	? (int)$arParams['partner_height_max'] 	: 0;
		$weightMin 	= !empty($arParams['partner_weight_min']) 	? (int)$arParams['partner_weight_min'] 	: 0;
		$weightMax 	= !empty($arParams['partner_weight_max']) 	? (int)$arParams['partner_weight_max'] 	: 0;

		return [
				'age' 		=> [
					new AgeValid($ageMin, $ageMax),
					new WeightValid($weightMin, $weightMax),
					new HeightValid($heightMin, $heightMax)
				]
		];
	}
}