<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Services\DataService;

class ProfileSecondRequest extends FormRequest
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(DataService $data)
	{
		$this->data = $data;
	}

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
	 * Prepare params for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'sex_orient'			=> $this->sex_orient < 1 || $this->sex_orient > 4 ? 2 : $this->sex_orient,
			'targets'				=> $this->data->serializeInput($this->targets),
			'speak_lang' 			=> $this->data->serializeInput($this->speak_lang),
			'body'		 			=> $this->body,
			'height'	 			=> $this->height < 150	? 149	: $this->height,
			'weight' 				=> $this->weight < 30	? 29 	: $this->weight,
			'hair_color'			=> $this->hair_color,
			'hair_type'				=> $this->hair_type,
			'eyes'					=> $this->eyes,
			'education'				=> $this->education,
			'smoke'					=> $this->smoke,
			'alcohol'				=> $this->alcohol,
			'family_status'			=> $this->family_status,
			'children'				=> $this->children,
			'help_money'			=> $this->help_money,
			'interests'				=> $this->data->serializeInput($this->interests),
			'icq'					=> (string)$this->icq,
			'url'					=> addslashes($this->url),
			'phone'					=> addslashes($this->phone),
			'description'			=> addslashes($this->description),
			'refresh_date'			=> date("Y-m-d"),
			'refresh_date_t'		=> time(),
			'session_time'			=> time(),
			'lastvisit'				=> time()
		]);
		if (!empty($this->description))
			$this->merge([
				'approved'			=> 0
			]);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'sex_orient'	 		=> ['integer'],
			'target_meet'			=> ['array'],
			'targets'				=> ['string'],
			'speak_lang'	 		=> ['string'],
			'body'		 			=> ['integer'],
			'height'	 			=> ['integer'],
			'weight' 				=> ['integer'],
			'hair_color'			=> ['integer'],
			'hair_type'				=> ['integer'],
			'eyes'					=> ['integer'],
			'education'				=> ['integer'],
			'smoke'					=> ['integer'],
			'alcohol'				=> ['integer'],
			'family_status'			=> ['integer'],
			'children'				=> ['integer'],
			'help_money'			=> ['integer'],
			'interests'				=> ['string'],
			'icq'					=> ['string'],
			'url'					=> ['string'],
			'phone'					=> ['string'],
			'description'			=> ['string'],
			'refresh_date'			=> ['string'],
			'refresh_date_t'		=> ['integer'],
			'session_time'			=> ['integer'],
			'lastvisit'				=> ['integer'],
			'approved'				=> ['integer']
		];
	}
}
