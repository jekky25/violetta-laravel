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
			'user_birth_date'		=> $this->data->getDateStr($this->birth_day, $this->birth_month, $this->birth_year),
			'refresh_date'			=> date("Y-m-d"),
			'refresh_date_t'		=> time(),
			'session_time'			=> time(),
			'lastvisit'				=> time(),
			'user_name'				=> $this->name,
			'user_sex'				=> $this->sex,
			'user_city'				=> $this->city,
			'user_region'			=> $this->region,
			'user_country'			=> $this->country
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
		$city						= !empty($arParams['city']) 	? (int)$arParams['city'] 	: 0;
		$region						= !empty($arParams['region']) 	? (int)$arParams['region'] 	: 0;
		$country					= !empty($arParams['country']) 	? (int)$arParams['country'] : 0;
		return [
			'user_name'					=> [
				'string',
				'required',
				'max:30',
				'min:2',
				new BirthData((int)$arParams['birth_day'], (int)$arParams['birth_month'], (int)$arParams['birth_year']),
				new BirthDataCorrect((int)$arParams['birth_day'], (int)$arParams['birth_month'])
			],
			'sex'					=> ['integer', 'required'],
			'city'					=> [
				'integer',
				new PlaceEmpty($city, $region, $country),
				new PlaceCorrect($city, $region, $country)
			],
			'region'				=> ['integer'],
			'country'				=> ['integer'],
			'user_city'				=> ['integer'],
			'user_region'			=> ['integer'],
			'user_country'			=> ['integer'],
			'user_birth_date'		=> ['string'],
			'refresh_date'			=> ['string'],
			'refresh_date_t'		=> ['integer'],
			'session_time'			=> ['integer'],
			'lastvisit'				=> ['integer'],
			'user_sex'				=> ['integer']
		];
	}
}
