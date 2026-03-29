<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckAuthorize;

class ScreenRequest extends FormRequest
{
	/**
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'description.required'			=> 'Комментарий не заполнен',
			'description.max'				=> 'Ваш комментарий слишком длинный',
			'description.CheckAuthorize'	=> 'Только авторизованные пользователи могут оставлять комментарии'
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
			'description'	=> ['required', 'max:1000', new CheckAuthorize],
		];
	}
}