<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Captcha implements ValidationRule
{
	/**
	* Indicates whether the rule should be implicit.
	*
	* @var bool
	*/
	public $implicit = true;

	/**
	* Run the validation rule.
	*
	* @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	*/
	public function validate(string $attribute, mixed $recaptcha_response, Closure $fail): void
	{
		$recaptcha_url 		= 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha_secret 	= RE_SEC_KEY;
		$ch = curl_init();
			curl_setopt_array($ch, [
			CURLOPT_URL => $recaptcha_url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
			'secret' 	=> $recaptcha_secret,
			'response' 	=> $recaptcha_response,
			'remoteip' 	=> $_SERVER['REMOTE_ADDR']
			],
            CURLOPT_RETURNTRANSFER => true
        ]);

        $output = curl_exec($ch);
        curl_close($ch);
        $recaptcha = json_decode($output);
		if ($recaptcha->success === false || $recaptcha->score < 0.2) $fail('Капча не пройдена');
	}
}