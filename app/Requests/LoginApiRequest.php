<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginApiRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

	public static $userNotFounded = 'Пользователь не найден';


	/**
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'login.required' 	=> 'Логин не введен',
			'password.required' => 'Пароль не введен'
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
			'login'					=> ['bail', 'required', 'string'],
			'password'				=> ['bail', 'required', 'string']
		];
	}
}
