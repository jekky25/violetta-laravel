<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\BanListInterface;
use App\Interfaces\CityInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\RegionInterface;
use App\Interfaces\DiaryInterface;
use App\Interfaces\PhotoInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\AnketVisitInterface;
use App\Requests\PassRequest;
use App\Requests\PhotoRequest;
use App\Requests\ProfileMainRequest;
use App\Requests\ProfileSecondRequest;
use App\Requests\ProfilePartnerRequest;
use App\Requests\ForgetPasswordRequest;
use App\Requests\RegistrationRequest;
use App\Requests\SettingRequest;
use App\Requests\LoginRequest;
use App\Services\FormatService;
use App\Services\MessageService;
use App\Mail\RegistrationEmail;
use App\Mail\ForgetPasswordEmail;
use App\Fields\RegistrationField;
use App\Fields\ProfileEditField;
use App\Fields\ProfileSecondField;
use App\Fields\ProfilePartnerField;

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

	public static $countPerPage 	= 10;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(
		protected BanListInterface $banListRepository,
		protected CityInterface $cityRepository,
		protected RegionInterface $regionRepository,
		protected CountryInterface $countryRepository,
		protected DiaryInterface $diaryRepository,
		protected PhotoInterface $photoRepository,
		protected UserInterface $userRepository,
		protected AnketVisitInterface $anketVisitRepository,
		protected MessageService $messageService
	) {}

	/**
	 * Show an edit short profile page
	 * @return \Illuminate\Http\Response
	 */
	public function edit(ProfileEditField $fields)
	{
		return response()->view(
			'registration.edit',
			[
				'userData'		=> Auth::user(),
				'fields'		=> $fields->get()
			]
		);
	}

	/**
	 * Show an edit full profile page
	 * @return \Illuminate\Http\Response
	 */
	public function second(ProfileSecondField $fields)
	{
		return response()->view(
			'registration.second',
			[
				'userData'		=> Auth::user(),
				'fields'		=> $fields->get()
			]
		);
	}

	/**
	 * Show an edit partner page
	 * @return \Illuminate\Http\Response
	 */
	public function partner(ProfilePartnerField $fields)
	{
		return response()->view(
			'registration.partner',
			[
				'userData'			=> Auth::user(),
				'fields'			=> $fields->get()
			]
		);
	}

	/**
	 * Show an edit page with the user pictures
	 * @return \Illuminate\Http\Response
	 */
	public function photo()
	{
		$user = $this->userRepository->getJustById(Auth::id(), ['photo']);
		return response()->view(
			'registration.photo',
			[
				'photos' => $user->photo
			]
		);
	}

	/**
	 * Show a change password page
	 * @return \Illuminate\Http\Response
	 */
	public function pass()
	{
		return response()->view('registration.pass');
	}

	/**
	 * Show a delete profile page
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		return response()->view('registration.delete');
	}

	/**
	 * Delete profile
	 * @return void
	 */
	public function destroyConfirm()
	{
		$user = Auth::user();
		$this->userRepository->destroy($user);
		return redirect()->route('registration.delete')->with('success', 'Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.');
	}

	/**
	 * Change user password
	 * @param  PassRequest $request
	 * @return void
	 */
	public function passPost(PassRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show an edit picture page
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function editPhoto($id)
	{
		$user 			= Auth::user();
		$photo			= $this->photoRepository->getByIdAndUserId($id, $user->id);
		return response()->view(
			'registration.photo_edit',
			[
				'photo' => $photo
			]
		);
	}

	/**
	 * Reupload an user picture
	 * @param  PhotoRequest  $request
	 * @param int $id
	 * @return void
	 */
	public function editPhotoUpdate(PhotoRequest $request, $id)
	{
		$user			= Auth::user();
		$photo			= $this->photoRepository->getByIdAndUserId($id, $user->id);
		$this->photoRepository->update($photo, $request->validated());
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Add an user picture
	 * @param PhotoRequest $request
	 * @return void
	 */
	public function photoStore(PhotoRequest $request)
	{
		$this->photoRepository->store(Auth::user(), $request->validated());
		return redirect()->back()
			->with('success', 'Фото успешно добавлено')
			->withInput();
	}

	/**
	 * Edit a short user profile
	 * @param  ProfileMainRequest $request
	 * @return void
	 */
	public function post(ProfileMainRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Edit a full user profile
	 * @param ProfileSecondRequest $request
	 * @return void
	 */
	public function secondPost(ProfileSecondRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Edit a partner profile
	 * @param  ProfilePartnerRequest $request
	 * @return void
	 */
	public function partnerPost(ProfilePartnerRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Delete an user picture
	 * @param int $id
	 * @return void
	 */
	public function destroyPhoto($id)
	{
		$title			= 'Информация';
		$text			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction	= route('registration.edit.photo.delete', $id);
		$this->messageService->outMessageInfo($title, $text, $confirmAction, method_field('DELETE'));
	}

	/**
	 * Delete an user picture
	 * @param  PhotoRequest $request
	 * @param int $id
	 * @return void
	 */
	public function destroyPhotoAction(PhotoRequest $request, $id)
	{
		$user 			= Auth::user();
		$photo			= $this->photoRepository->getByIdAndUserId($id, $user->id);
		$arParams 		= $request->post();
		if (!empty($arParams['cancel'])) return redirect()->route('registration.edit.photo');
		if (!empty($arParams['confirm'])) {
			$this->photoRepository->destroyPhoto($photo);
			return redirect()->route('registration.edit.photo')->with('success', 'Информация сохранена.');
		}
	}

	/**
	 * Show a user diary page
	 * @return \Illuminate\Http\Response
	 */
	public function diary()
	{
		$user 			= Auth::user();
		$diaries 		= $this->diaryRepository->getByUser(self::$countPerPage, $user->id);
		return response()->view(
			'registration.diary',
			[
				'diaries' 		=> $diaries
			]
		);
	}

	/**
	 * Show a setting page
	 * @return \Illuminate\Http\Response
	 */
	public function settings()
	{
		return response()->view('registration.settings');
	}

	/**
	 * Update user settings
	 * @param SettingRequest $request
	 * @return void
	 */
	public function settingsPost(SettingRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show a top100 page
	 * @return \Illuminate\Http\Response
	 */
	public function top100(FormatService $format)
	{
		return response()->view(
			'registration.top100',
			[
				'textTop100'	=> $format->getTextTop100(),
				'formToTop'		=> $format->getFormToTop()
			]
		);
	}

	/**
	 * update top100
	 * @return void
	 */
	public function top100Post(FormatService $format)
	{
		$user 			= Auth::user();
		if (count($user->photo) == 0) {
			return redirect()->route('registration.top100')->with(
				[
					'textTop100'	=> $format->getTextTop100Update(),
					'formToTop'		=> $format->getFormToTopUpdate()
				]
			);
		}
		$this->userRepository->update($user, ['top100' => time()]);
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * logout
	 * @return void
	 */
	public function logout()
	{
		Auth::logout();
		return redirect()->route('home');
	}

	/**
	 * login
	 * @param LoginRequest $request
	 * @return void
	 */
	public function login(LoginRequest $request)
	{
		$arParams		= $request->validated();
		$remember		= true;
		$user			= $this->userRepository->getByLoginAndPass($arParams['username_template'], $arParams['pass_template']);
		if (empty($user)) {
			$title			= 'Информация';
			$text			= 'Неверно указаны имя пользователя или пароль!';
			$this->messageService->outMessageDie($title, $text);
		} else
			Auth::login($user, $remember);
		return redirect()->route('home');
	}

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
			session('success') ? 'registration.finish' : 'registration.registration',
			['fields'		=> $fields->get()]
		);
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
