<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\BirthData;
use App\Rules\BirthDataCorrect;
use App\Rules\PlaceEmpty;
use App\Rules\PlaceCorrect;
use App\Services\DataService;

class ProfileMainRequest extends FormRequest
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
			'name.required'		 	=> 'Имя не заполнено',
			'name.max'		 		=> 'Имя слишком длинное',
			'name.min'		 		=> 'Имя слишком короткое',
			'sex.required'		 	=> 'Вы не указали пол',
			'BirthData'				=> 'Не указана дата рождения',
			'BirthDataCorrect'		=> 'Некорректная дата рождения',
			'PlaceEmpty'			=> 'Не указано место жительства',
			'PlaceCorrect'			=> 'Неверно указано место жительства'
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
			'birth_date'			=> $this->data->getDateStr($this->birth_day, $this->birth_month, $this->birth_year),
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$arParams					= $this->post();
		$city						= !empty($arParams['city_id']) 		? (int)$arParams['city_id'] 	: 0;
		$region						= !empty($arParams['region_id']) 	? (int)$arParams['region_id'] 	: 0;
		$country					= !empty($arParams['country_id']) 	? (int)$arParams['country_id'] : 0;
		return [
			'name'					=> [
				'string',
				'required',
				'max:30',
				'min:2',
				new BirthData((int)$arParams['birth_day'], (int)$arParams['birth_month'], (int)$arParams['birth_year']),
				new BirthDataCorrect((int)$arParams['birth_day'], (int)$arParams['birth_month'])
			],
			'sex'					=> ['integer', 'required'],
			'city_id'					=> [
				'integer',
				new PlaceEmpty($city, $region, $country),
				new PlaceCorrect($city, $region, $country)
			],
			'region'				=> ['integer'],
			'country_id'			=> ['integer'],
			'region_id'				=> ['integer'],
			'birth_date'			=> ['string'],
			'sex'					=> ['integer']
		];
	}
}
