<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class DiaryCommentRequest extends FormRequest
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
			'description.required' 	=> 'Описание не заполнено',
			'description.max'	 	=> 'Описание слишком длинное',
			'description.min'	 	=> 'Описание слишком короткое',
			'title.required'	 	=> 'Заголовок не заполнен',
			'title.max'	 			=> 'Заголовок слишком длинный',
			'title.min'	 			=> 'Заголовок слишком короткий',
			'photo_link.image'		=> 'Файл не является изображением',
			'photo_link.max'		=> 'Файл слишком большой',
		];
	}

	/**
	* rules for the request
	* @return string array
	*/
	public function rules():array
	{
		return [
			'description'	=> ['required', 'max:3000', 'min:2'],
			'title'			=> ['max:255'],
			'photo_link'	=> ['file', 'image', 'max:4048'],
		];
	}
}