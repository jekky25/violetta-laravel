<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\PlaceEmpty;
use App\Rules\PlaceCorrect;
use J25\GoogleCaptcha\GoogleCaptcha;
use App\Rules\CheckBan;
use App\Rules\CheckLogin;
use App\Rules\CheckEmail;
use App\Rules\CheckPassword;
use App\Rules\BirthData;
use App\Rules\BirthDataCorrect;
use App\Services\DataService;

class RegistrationRequest extends FormRequest
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
			'login.required'					=> 'Логин не заполнен',
			'login.CheckBan'					=> 'Вы забанены из-за нарушения правил нашего сайта, по всем вопросам обращайтесь к администрации сайта',
			'login.min'							=> 'Логин меньше :min символов',
			'login.max'							=> 'Логин больше :max символов',
			'login.regex'						=> 'При заполнении логина допускается использовать только цифры, буквы латинского алфавита и нижнее подчеркивание',
			'login.CheckLogin'					=> 'Пользователь с таким логином уже существует, выберите другой логин',
			'password.required'					=> 'Пароль не заполнен',
			'password.min'						=> 'Пароль меньше :min символов',
			'password.regex'					=> 'При заполнении пароля допускается использовать только цифры, буквы латинского алфавита и нижнее подчеркивание',
			'password.CheckPassword'			=> 'Введенные пароли не совпадают',
			'name.required'		 				=> 'Имя не заполнено',
			'name.max'		 					=> 'Имя больше :max символов',
			'name.min'		 					=> 'Имя меньше :min символов',
			'sex.required'		 				=> 'Вы не указали пол',
			'BirthData'							=> 'Не указана дата рождения',
			'birthDataCorrect'					=> 'Некорректная дата рождения',
			'mail.required'		 				=> 'Не указан Е-майл',
			'mail.regex'				 		=> 'Указан некорректный Е-майл',
			'mail.CheckEmail'		 			=> 'Пользователь с таким Е-майл уже зарегистрирован',
			'PlaceEmpty'						=> 'Не указано место жительства',
			'PlaceCorrect'						=> 'Неверно указано место жительства',
			'recaptcha_response.required'		=> 'Капча не пройдена',
			'recaptcha_response.GoogleCaptcha'	=> 'Капча не пройдена',
			'conditions.required'				=> 'Пожалуйста, согласитесь с нашими условиями'
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
			'user_active' 				=> 1,
			'approved'		 			=> 1,
			'user_login' 				=> $this->login,
			'user_password' 			=> $this->password,
			'user_hash'	 				=> md5($this->password),
			'user_mail' 				=> $this->mail,
			'user_sex' 					=> $this->sex,
			'user_name' 				=> $this->name,
			'user_birth_date'	 		=> $this->data->getDateStr($this->birth_day, $this->birth_month, $this->birth_year),
			'user_country' 				=> $this->country,
			'user_region' 				=> $this->region,
			'user_city'					=> $this->city,
			'user_make_date'	 		=> date("Y-m-d"),
			'user_make_date_t' 			=> time(),
			'user_refresh_date'			=> date("Y-m-d"),
			'user_refresh_date_t'		=> time(),
			'session_time'				=> time(),
			'lastvisit'					=> time(),
			'ip'						=> $this->ip(),
			'submit_code' 				=> md5(time() . $this->login . rand(0, 1000)),
			'description'	 			=> "",
			'partner_description'		=> "",
			'confirm_email' 			=> 0
		]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$arParams 				= $this->post();
		$city 					= !empty($arParams['city']) 				? (int)$arParams['city'] 			: 0;
		$region 				= !empty($arParams['region']) 				? (int)$arParams['region'] 			: 0;
		$country 				= !empty($arParams['country']) 				? (int)$arParams['country'] 		: 0;
		$password 				= !empty($arParams['password'])				? $arParams['password'] 			: '';
		$passwordSecond 		= !empty($arParams['password_second'])		? $arParams['password_second']		: '';

		return [
			'login'						=> ['string', 'required', new CheckBan, 'max:20', 'min:4', "regex:/^[0-9a-zA-Z_]+$/", new CheckLogin],
			'user_login'				=> ['string'],
			'password'					=> ['required', 'max:20', 'min:4', "regex:/^[0-9a-zA-Z_]+$/", new CheckPassword($password, $passwordSecond)],
			'name'						=> [
				'required',
				'max:30',
				'min:2',
				new BirthData((int)$arParams['birth_day'], (int)$arParams['birth_month'], (int)$arParams['birth_year']),
				new BirthDataCorrect((int)$arParams['birth_day'], (int)$arParams['birth_month'])
			],
			'sex'						=> ['integer', 'required'],
			'mail'						=> ['required', "regex:/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}|museum$/i", new CheckEmail],
			'country'					=> [
				'integer',
				new PlaceEmpty($city, $region, $country),
				new PlaceCorrect($city, $region, $country)
			],
			'region'					=> ['integer'],
			'city'						=> ['integer'],
			'recaptcha_response'	 	=> ['required', new GoogleCaptcha],
			'conditions'				=> ['required'],
			'user_active'				=> ['integer'],
			'approved'					=> ['integer'],
			'user_password'				=> ['string'],
			'user_hash'					=> ['string'],
			'user_mail'					=> ['string'],
			'user_sex'					=> ['integer'],
			'user_name'					=> ['string'],
			'user_birth_date'			=> ['string'],
			'user_country'				=> ['integer'],
			'user_region'				=> ['integer'],
			'user_city'					=> ['integer'],
			'user_make_date'			=> ['string'],
			'user_make_date_t'			=> ['integer'],
			'user_refresh_date'			=> ['string'],
			'user_refresh_date_t'		=> ['integer'],
			'session_time'				=> ['integer'],
			'lastvisit'					=> ['integer'],
			'ip'						=> ['string'],
			'submit_code'				=> ['string'],
			'description' 				=> ['string'],
			'partner_description'		=> ['string'],
			'confirm_email'		 		=> ['integer']
		];
	}
}
