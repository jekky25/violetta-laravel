<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckOften;

class PrivmsgRequest extends FormRequest
{
	/**
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'description.required' 		=> 'Сообщение не заполнено',
			'description.max'	 		=> 'Сообщение слишком длинное',
			'description.min'	 		=> 'Сообщение слишком короткое',
			'check_often'				=> 'Вы превысили лимит отправляемых сообщений:<br /> не более 10 сообщений за 5 минут'
		];
	}

	/**
	 * rules for the request
	 * @return string array
	 */
	public function rules(): array
	{
		return [
			'description'	=> ['required', 'max:3000', 'min:2', new CheckOften()]
		];
	}
}
