<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;


class ContactsController extends Controller
{
	public static $rules = [
		'name'					=> ['required', 'max:30', 'min:2'],
		'mail'					=> ['required', "regex:/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}|museum$/i"],
		'recaptcha_response' 	=> ['required', 'capcha'],
	];

	public static $errMessages = [
		'name.required'		 			=> 'Имя не заполнено',
		'name.max'		 				=> 'Имя слишком длинное',
		'name.min'		 				=> 'Имя слишком короткое',
		'mail.required'					=> 'не указан Е-майл',
		'mail.regex'	 				=> 'Указан некорректный Е-майл',
		'recaptcha_response.required'	=> 'Капча не пройдена',
		'recaptcha_response.capcha'		=> 'Капча не пройдена'
	];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        // $this->middleware('auth');
	}


	public function index()
	{
		return response()->view ('contacts');
	}

	public function post(Request $request)
	{
		$arParams 				= $request->post();
		$recaptcha_response  	= !empty ($arParams['recaptcha_response']) 	? $arParams['recaptcha_response'] 	: '';

		Validator::extend('capcha',
		function () use ($recaptcha_response) {
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
			if ($recaptcha->success === true && $recaptcha->score >= 0.2) return true;
			return false;
		});

		$validator = Validator::make($arParams, self::$rules, self::$errMessages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;
			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}
	}
}

