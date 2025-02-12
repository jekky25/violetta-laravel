<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserBirthdayRequest extends FormRequest
{
	public $countPerPage 	= 10;

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		return [];
	}
}