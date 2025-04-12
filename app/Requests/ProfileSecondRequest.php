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
			'user_sex_orient'		=> $this->sex_orient < 1 || $this->sex_orient > 4 ? 2 : $this->sex_orient,
			'targets'				=> $this->data->serializeInput($this->targets),
			'user_speak_lang' 		=> $this->data->serializeInput($this->speak_lang),
			'user_body' 			=> $this->body,
			'user_height' 			=> $this->height < 150	? 149	: $this->height,
			'user_weight' 			=> $this->weight < 30	? 29 	: $this->weight,
			'user_hair_color'		=> $this->hair_color,
			'user_hair_type'		=> $this->hair_type,
			'user_eyes'				=> $this->eyes,
			'user_education'		=> $this->education,
			'user_smoke'			=> $this->smoke,
			'user_spirt'			=> $this->spirt,
			'user_sem_polozh'		=> $this->family_status,
			'user_children'			=> $this->children,
			'user_help_money'		=> $this->help_money,
			'interests'				=> $this->data->serializeInput($this->interests),
			'icq'					=> (string)$this->icq,
			'url'					=> addslashes($this->url),
			'phone'					=> addslashes($this->phone),
			'description'			=> addslashes($this->description),
			'user_refresh_date'		=> date("Y-m-d"),
			'user_refresh_date_t'	=> time(),
			'user_session_time'		=> time(),
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
			'user_sex_orient' 		=> ['integer'],
			'target_meet'			=> ['array'],
			'targets'				=> ['string'],
			'speak_lang'			=> ['array'],
			'user_speak_lang' 		=> ['string'],
			'user_body'		 		=> ['integer'],
			'user_height' 			=> ['integer'],
			'user_weight' 			=> ['integer'],
			'user_hair_color'		=> ['integer'],
			'user_hair_type'		=> ['integer'],
			'user_eyes'				=> ['integer'],
			'user_education'		=> ['integer'],
			'user_smoke'			=> ['integer'],
			'user_spirt'			=> ['integer'],
			'user_sem_polozh'		=> ['integer'],
			'user_children'			=> ['integer'],
			'user_help_money'		=> ['integer'],
			'interests'				=> ['string'],
			'icq'					=> ['string'],
			'url'					=> ['string'],
			'phone'					=> ['string'],
			'description'			=> ['string'],
			'user_refresh_date'		=> ['string'],
			'user_refresh_date_t'	=> ['integer'],
			'user_session_time'		=> ['integer'],
			'lastvisit'				=> ['integer'],
			'approved'				=> ['integer']
		];
	}
}
