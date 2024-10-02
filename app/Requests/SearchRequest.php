<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SearchRequest extends FormRequest
{
	public $countPerPage 	= 10;

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
			'sex'				=> ['integer'],
			'find_sex'			=> ['integer'],
			'photo'				=> ['alpha_num'],
			'online'			=> ['alpha_num'],
			'age_min'			=> ['integer'],
			'age_max'			=> ['integer'],
			'height_min'		=> ['integer'],
			'height_max'		=> ['integer'],
			'weight_min'		=> ['integer'],
			'weight_max'		=> ['integer'],
			'country'			=> ['integer'],
			'region'			=> ['integer'],
			'city'				=> ['integer'],
			'body'				=> ['integer'],
			'hair_type'			=> ['integer'],
			'anket_per_page'	=> ['integer'],
			'eyes'				=> ['integer'],
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
            'sex'				=> !empty($this->sex)				? $this->sex			: 0,
			'find_sex'			=> !empty($this->find_sex)			? $this->find_sex		: 0,
			'photo'				=> !empty($this->photo)				? $this->photo			: 0,
			'online'			=> $this->getOnline(),
			'age_min'			=> !empty($this->age_min)			? $this->age_min		: AGE_MIN,
			'age_max'			=> !empty($this->age_max)			? $this->age_max		: AGE_MAX,
			'height_min'		=> !empty($this->height_min)		? $this->height_min		: HEIGHT_MIN,
			'height_max'		=> !empty($this->height_max)		? $this->height_max		: HEIGHT_MAX,
			'weight_min'		=> !empty($this->weight_min)		? $this->weight_min		: WEIGHT_MIN,
			'weight_max'		=> !empty($this->weight_max)		? $this->weight_max		: WEIGHT_MAX,
			'country'			=> !empty($this->country)			? $this->country 		: 0,
			'region' 			=> !empty($this->region)			? $this->region 		: 0,
			'city' 				=> !empty($this->city)				? $this->city 			: 0,
			'body'				=> !empty($this->body)				? $this->body 			: 0,
			'hair_type' 		=> !empty($this->hair_type)			? $this->hair_type 		: 0,
			'eyes'				=> !empty($this->eyes)				? $this->eyes 			: 0,
			'anket_per_page'	=> !empty($this->anket_per_page)	? $this->anket_per_page : $this->countPerPage
        ]);
    }

	/**
	* Prepare the param online end get it
	*
	* @return int|string
	*/
	protected function getOnline()
	{
		$online				= isset ($this->online)				? $this->online 				: '';
		return ($online == 1 || $online == 'on')				? 1 							: $online;
	}
}