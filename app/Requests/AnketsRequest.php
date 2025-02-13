<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnketsRequest extends FormRequest
{
	public $countPerPage 	= 10;
	private $merge			= [];
	private $default		= [
		'men'			=> MEN,
		'women'			=> WOMEN
	];

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		return [
			'sex'				=> ['nullable','string'],
			'get_sex'			=> ['nullable','integer'],
			'age'				=> ['nullable','integer']
		];
	}

	/**
	* Prepare params for validation
	*
	* @return void
	*/
	protected function prepareForValidation()
    {
		$this->prepare();
		$this->merge($this->merge);
    }

	/**
     * Preparation valuables to types
     *
     * @return void
     */
	protected function prepare()
	{
		$this->merge['sex']		= isset($this->sex) && $this->sex == 'women' ? $this->sex : 'men';
		$this->merge['get_sex'] = $this->default[$this->merge['sex']];
		$this->merge['age']		= $this->age;
	}
}