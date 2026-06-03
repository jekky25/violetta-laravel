<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoCommentRequest extends FormRequest
{
	/**
	 * messages for the request
	 * @return string array
	 */
	public function messages(): array
	{
		return	[
			'description.required' 	=> 'Комментарий не заполнен',
			'description.max'	 	=> 'Ваш комментарий слишком длинный',
			'description.min'	 	=> 'Ваш комментарий слишком короткий',
		];
	}

	/**
	 * rules for the request
	 * @return string array
	 */
	public function rules(): array
	{
		return [
			'description'	=> ['required', 'max:1000', 'min:2']
		];
	}
}
