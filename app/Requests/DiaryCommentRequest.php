<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Route;

class DiaryCommentRequest extends FormRequest
{
	private $routeCommentDelete 	= 'ank.diary.comment.delete.id';
	private $routeCommentEdit 		= 'ank.diary.comment.edit.id';
	private $routeCommentDeleteActive	= 'ank.diary.comment.delete.action.id';
	
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
		if ($this->cancelRules()) return [];
		return [
			'description'	=> ['required', 'max:3000', 'min:2'],
			'title'			=> ['max:255'],
			'photo_link'	=> ['file', 'image', 'max:4048']
		];
	}

	/**
	* check routes for cancel
	* @return bool
	*/
	private function cancelRules()
	{
		if (Route::currentRouteName() == $this->routeCommentDelete) return true;
		if (Route::currentRouteName() == $this->routeCommentDeleteActive) return true;
		if (Route::currentRouteName() == $this->routeCommentEdit && $this->isMethod('get')) return true;
		return false;
	}

	/**
	* Get the validated data from the request.
	*
	* @param  array|int|string|null  $key
	* @param  mixed  $default
	* @param  integer $id
	* @param  integer $user_id
	* @return mixed
	*/
	public function validated($key = null, $default = null, $id = 0, $user_id = 0)
    {
		$arParams = $this->validator->validated();
		if ($this->cancelRules()) return data_get($arParams, $key, $default);
		$arParams['title']						= strip_tags($arParams['title'],"<b><strong><i>");
		$arParams['comment_title']				= $arParams['title'];
		$arParams['description']				= strip_tags($arParams['description'],"<b><strong><i>");
		$arParams['comment_text']				= $arParams['description'];
		$arParams['comment_time']				= time();
		$arParams['comment_dnevnik_id']			= $id;
		$arParams['comment_dnevnik_user_id']	= $user_id;
		return data_get($arParams, $key, $default);
	}
}