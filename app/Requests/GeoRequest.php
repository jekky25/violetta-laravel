<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeoRequest extends FormRequest
{
	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		return [
		];
	}
}