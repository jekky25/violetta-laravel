<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\UserInterface;
use App\Requests\ForgetPasswordRequest;
use App\Requests\RegistrationRequest;
use App\Mail\RegistrationEmail;
use App\Mail\ForgetPasswordEmail;
use App\Fields\RegistrationField;

class RegistrationController extends Controller
{
	public static $siteUrl				= 'www.avioletta.ru';
	public static $siteUrlWithProtocol	= 'http://www.avioletta.ru';
	public static $languageCodes = [
		'1' => 'rus',
		'2' => 'ukr',
		'3' => 'bel',
		'4' => 'gru',
		'5' => 'eng',
		'6' => 'ger',
		'7' => 'fra',
		'8' => 'spa',
		'9' => 'ita',
		'10' => 'chi',
		'11' => 'jap',
		'12' => 'arm'
	];

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(protected UserInterface $userRepository) {}

	/**
	 * show a forget password page
	 * @return \Illuminate\Http\Response
	 */
	public function forgetPass()
	{
		return response()->view('registration.forget_pass');
	}

	/**
	 * send email from the forget password page
	 * @param  ForgetPasswordRequest $request
	 * @return void
	 */
	public function forgetPassPost(ForgetPasswordRequest $request)
	{
		$arParams 		= $request->validated();
		$email			= $arParams['email'];
		$user	= $this->userRepository->getByEmail($email);
		if (!empty($user)) {
			Mail::mailer(config('mail.mail_mode'))
				->to($email)
				->send(new ForgetPasswordEmail($user));
		}
		return redirect()->route(Route::currentRouteName())->with('success', '<p>На адрес <strong>' . $email . '</strong> было выслано письмо с вашим паролем!');
	}

	/**
	 * show a registration page
	 * @return \Illuminate\Http\Response
	 */
	public function registration(RegistrationField $fields)
	{
		return response()->view(
			session('success') ? 'registration.finish' :
								 'registration.registration',
								 ['fields' => $fields]);
	}

	/**
	 * post data after registration and send e-mail with registration information
	 * @param  RegistrationRequest $request
	 * @return void
	 */
	public function registrationStore(RegistrationRequest $request)
	{
		$arParams = $request->validated();
		$this->userRepository->create($arParams);
		$user	= $this->userRepository->getByLogin($arParams['login']);
		Auth::login($user, true);
		Mail::mailer(config('mail.mail_mode'))
			->to($user->email)
			->send(new RegistrationEmail($user));
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * show confirm registration page and update data
	 * @param int $id
	 * @param string $code
	 * @return \Illuminate\Http\Response
	 */
	public function confirm($id, $code)
	{
		if (empty($code)) abort(404);
		$user 			= $this->userRepository->getByIdAndConfirmCode($id, $code);
		$this->userRepository->update($user, [
			'confirm_email'			=> 1,
			'submit_code'			=> ''
		]);
		return response()->view(
			'registration.confirm',
			[
				'isConfirmed'			=> true,
			]
		);
	}
}
