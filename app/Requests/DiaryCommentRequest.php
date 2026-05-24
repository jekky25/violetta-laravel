<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiaryCommentRequest extends FormRequest
{
	/**
	 * messages for the request
	 */
	public function messages(): array
	{
		return	[
			'description.required' 	=> 'Описание не заполнено',
			'description.max'	 	=> 'Описание слишком длинное',
			'description.min'	 	=> 'Описание слишком короткое',
			'title.required'	 	=> 'Заголовок не заполнен',
			'title.max'	 			=> 'Заголовок слишком длинный',
			'title.min'	 			=> 'Заголовок слишком короткий',
			'photo.image'			=> 'Файл не является изображением',
			'photo.max'				=> 'Файл слишком большой',
		];
	}

	/**
	 * rules for the request
	 */
	public function rules(): array
	{
		return [
			'description'	=> ['required', 'max:3000', 'min:2'],
			'title'			=> ['max:255'],
			'photo'			=> ['nullable', 'file', 'image', 'max:4048']
		];
	}
}
