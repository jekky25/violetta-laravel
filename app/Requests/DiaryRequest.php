<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class DiaryRequest extends FormRequest
{
	private $routeDelete		= 'ank.diary.delete.id';
	private $routeDeleteActive	= 'ank.diary.delete.action.id';
	private $routeEdit			= 'ank.diary.edit.id';

	/**
	* messages for the request
	* @return string array
	*/
	public function messages():array
	{
		return	[
			'description.required' 	=> 'Описание не заполнено',
			'description.max'		=> 'Описание слишком длинное',
			'description.min'		=> 'Описание слишком короткое',
			'title.required'		=> 'Заголовок не заполнен',
			'title.max'				=> 'Заголовок слишком длинный',
			'title.min'				=> 'Заголовок слишком короткий',
			'photo.image'			=> 'Файл не является изображением',
			'photo.max'				=> 'Файл слишком большой',
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
			'title'			=> ['required', 'max:255', 'min:2'],
			'photo'			=> ['file', 'image', 'max:4048'],
		];
	}

	/**
	* check routes for cancel
	* @return bool
	*/
	private function cancelRules()
	{
		if (Route::currentRouteName() == $this->routeDelete) return true;
		if (Route::currentRouteName() == $this->routeDeleteActive) return true;
		if (Route::currentRouteName() == $this->routeEdit && $this->isMethod('get')) return true;
		return false;
	}
}