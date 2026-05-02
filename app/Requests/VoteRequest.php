<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
{
	/**
	* messages for the request
	* @return array<string>
	*/
	public function messages():array
	{
		return	[
			'vote.required'		=> 'Оценка не заполнена',
			'vote.integer'		=> 'Оценка не является числом',
			'vote.min'			=> 'Оценка должна быть в пределе от 1 до 5',
			'vote.max'			=> 'Оценка должна быть в пределе от 1 до 5'
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
			'vote' => ['required','integer', 'min:1', 'max:5']
		];
	}
}