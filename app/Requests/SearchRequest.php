<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SearchRequest extends FormRequest
{
	public $countPerPage 	= 10;
	private $merge			= [];
	private $default		= [
		'age_min'			=> AGE_MIN,
		'age_max'			=> AGE_MAX,
		'height_min'		=> HEIGHT_MIN,
		'height_max'		=> HEIGHT_MAX,
		'weight_min'		=> WEIGHT_MIN,
		'weight_max'		=> WEIGHT_MAX,
		'anket_per_page'	=> 10
	];

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
	* Get the validation rules that apply to the request.
	*
	* @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	*/
	public function rules(): array
	{
		return [
			'sex'				=> ['nullable','integer'],
			'find_sex'			=> ['nullable','integer'],
			'photo'				=> ['nullable','boolean'],
			'online'			=> ['nullable','boolean'],
			'age_min'			=> ['nullable', 'integer'],
			'age_max'			=> ['nullable', 'integer'],
			'height_min'		=> ['nullable', 'integer'],
			'height_max'		=> ['nullable', 'integer'],
			'weight_min'		=> ['nullable', 'integer'],
			'weight_max'		=> ['nullable', 'integer'],
			'country'			=> ['nullable','integer'],
			'region'			=> ['nullable','integer'],
			'city'				=> ['nullable','integer'],
			'body'				=> ['nullable','integer'],
			'hair_type'			=> ['nullable','integer'],
			'anket_per_page'	=> ['nullable','integer'],
			'eyes'				=> ['nullable','integer']
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
		foreach ($this->rules() as $name => $rule)
		{
			if(in_array('boolean', $rule) && $this->{$name} == true) $this->merge[$name] = $this->toBoolean($this->{$name});
			if(in_array('integer', $rule)) $this->merge[$name] = $this->toInteger($this->{$name}, $this->getDefault($name));
		}
	}

	/**
     * Convert to boolean
     *
     * @param $val
     * @return boolean
     */
    protected function toBoolean($val)
    {
        return filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

	/**
     * Convert to integer
     *
     * @param $val
	 * @param int $default
     * @return integer
     */
	protected function toInteger($val, $default)
    {
        return !empty($val) ? (int)$val : $default;
    }

	/**
     * Get default by name
     *
     * @param string $name
     * @return integer
     */
	protected function getDefault($name)
	{
		return isset($this->default[$name]) ? $this->default[$name] : 0;
	}
}