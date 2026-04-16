<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoRequest extends FormRequest
{
	/**
	* messages for the request
	* @return string array
	*/
	public function messages():array
	{
		return	[
			'photo.image'		=> 'Файл не является изображением',
			'photo.max'			=> 'Файл слишком большой',
			'photo.required'	=> 'Файл не был загружен'
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
			'photo'	=> ['required','file', 'image', 'max:4048']
		];
	}
}