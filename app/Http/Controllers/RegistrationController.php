<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Helpers\Helper;
use App\Services\DataService;
use App\Services\FormatService;
use App\Services\MessageService;
use App\Mail\Email;

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
	)
	{
	}

	/**
	* Show an edit short profile page
	* @return \Illuminate\Http\Response
	*/
	public function edit(DataService $data)
	{
		$user		= Auth::user();
		$days		= $data->getDays();
		$months		= $data->getMonths();
		$years		= $data->getYears();
		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old ('country', $user->user_country);
		$regionId	= (int) old ('region', $user->user_region);
		$regions	= $countryId > 0 	? $this->regionRepository->getByCountryId($countryId) 	: [];
		$cities		= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: [];

		return response()->view('registration.edit',
		[
			'userData'		=> $user,
			'days'			=> $days,
			'months'		=> $months,
			'years'			=> $years,
			'countries'		=> $countries,
			'regions'		=> $regions,
			'cities'		=> $cities,
		]);
	}

	/**
	* Show an edit full profile page
	* @return \Illuminate\Http\Response
	*/
	public function second(FormatService $formService)
	{
		$user			= Auth::user();
		$sexOrient		= $formService->BlockSelect('sex_orient',SEX_ORIENT_CLASS,$user->user_sex_orient,2);
		$meetTarget		= $formService->BlockSelect('meet_target',MEET_TARGET_CLASS,$user->meet_target,2);
		$userSpeakLang	= $formService->preparePropfromArray($user->speak_lang, self::$languageCodes);
		$body 			= $formService->BlockSelect("body",BODY_CLASS,$user->user_body,2);
		$heights 		= $formService->getHeights();
		$weights 		= $formService->getWeights();
		$hairColor 		= $formService->BlockSelect("hair_color",HAIR_COLOR_CLASS,$user->user_hair_color,2);
		$hairType 		= $formService->BlockSelect("hair_type",HAIR_TYPE_CLASS,$user->user_hair_type,2);
		$eyes	 		= $formService->BlockSelect("eyes",EYES_CLASS,$user->user_eyes,2);
		$education 		= $formService->BlockSelect("education",EDUCATION_CLASS,$user->user_education,2);
		$smoke 			= $formService->BlockSelect("smoke",SMOKE_CLASS,$user->user_smoke,2);
		$spirt			= $formService->BlockSelect("spirt",SPIRT_CLASS,$user->user_spirt,2);
		$familyStatus	= $formService->BlockSelect("family_status",FAMILY_STATUS_CLASS,$user->user_sem_polozh,2);
		$children		= $formService->BlockSelect("children",CHILDREN_CLASS,$user->user_children,2);
		$helpMoney		= $formService->BlockSelect("help_money",HELP_MONEY_CLASS,$user->user_help_money,2);
		$interest		= $formService->BlockSelect("interest",INTEREST_CLASS,$user->interests,2);

		return response()->view('registration.second',
		[
			'userData'		=> $user,
			'sexOrient'		=> $sexOrient,
			'meetTarget'	=> $meetTarget,
			'userSpeakLang'	=> $userSpeakLang,
			'body'			=> $body,
			'heights'		=> $heights,
			'weights'		=> $weights,
			'hairColor'		=> $hairColor,
			'hairType'		=> $hairType,
			'eyes'			=> $eyes,
			'education'		=> $education,
			'smoke'			=> $smoke,
			'spirt'			=> $spirt,
			'familyStatus'	=> $familyStatus,
			'children'		=> $children,
			'helpMoney'		=> $helpMoney,
			'interest'		=> $interest,
		]);
	}

	/**
	* Show an edit partner page
	* @return \Illuminate\Http\Response
	*/
	public function partner(DataService $data, FormatService $formService)
	{
		$user				= Auth::user();
		$age				= $data->getAges();
		$heights			= $formService->getHeights();
		$weights			= $formService->getWeights();
		$partnerBody		= $formService->BlockSelect("partner_body[]",BODY_CLASS, old ('partner_body', $user->user_partner_body),2);
		$partnerSpeakLang	= $formService->BlockSelect("partner_speak_lang[]",SPEAK_LANG_CLASS, old ('partner_speak_lang', $user->user_partner_speak_lang),2);
		$partnerSpirt		= $formService->BlockSelect("partner_spirt[]",SPIRT_CLASS, old ('partner_spirt', $user->user_partner_spirt),2);
		$partnerSmoke		= $formService->BlockSelect("partner_smoke[]",SMOKE_CLASS, old ('partner_smoke', $user->user_partner_smoke),2);
		$partnerEducation	= $formService->BlockSelect("partner_education[]",EDUCATION_CLASS, old ('partner_education', $user->user_partner_education),2);

		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old ('country', $user->user_partner_country);
		$regionId	= (int) old ('region', $user->user_partner_region);
		$regions	= $countryId > 0	? $this->regionRepository->getByCountryId($countryId) 	: [];
		$cities		= $regionId	> 0		? $this->cityRepository->getByRegionId($regionId) 		: [];

		return response()->view('registration.partner',
		[
			'userData'			=> $user,
			'age'				=> $age,
			'heights'			=> $heights,
			'weights'			=> $weights,
			'partnerBody'		=> $partnerBody,
			'partnerSpeakLang'	=> $partnerSpeakLang,
			'partnerSpirt'		=> $partnerSpirt,
			'partnerSmoke'		=> $partnerSmoke,
			'partnerEducation'	=> $partnerEducation,
			'countries'			=> $countries,
			'regions'			=> $regions,
			'cities'			=> $cities,
		]);
	}

	/**
	* Show an edit page with the user pictures
	* @return \Illuminate\Http\Response
	*/
	public function photo()
	{
		$user = $this->userRepository->getJustById(Auth::id(), ['photo']);
		return response()->view('registration.photo',
		[
			'photos' => $user->photo
		]);
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
		return redirect()->route('registration.delete')->with('success','Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.');
	}

	/**
	* Change user password
	* @param  PassRequest $request
	* @return void
	*/
	public function passPost(PassRequest $request)
	{
		$user 			= Auth::user();
		$this->userRepository->passUpdate($user, $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Show an edit picture page
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function editPhoto($id)
	{
		$user 			= Auth::user();
		$photo = $this->photoRepository->getByIdAndUserId($id, $user->user_id);
		return response()->view ('registration.photo_edit',
		[
			'photo' => $photo
		]);
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
		$photo			= $this->photoRepository->getByIdAndUserId($id, $user->user_id);
		$this->photoRepository->update($photo, $request->validated());
		return redirect()->back()
		->with('success','Фото успешно добавлено')
		->withInput();
	}

	/**
	* Add an user picture
	* @param PhotoRequest $request
	* @return void
	*/
	public function photoStore(PhotoRequest $request)
	{
		$user 			= Auth::user();
		$this->photoRepository->store($user, $request->validated());
			return redirect()->back()
			->with('success','Фото успешно добавлено')
			->withInput();
	}

	/**
	* Edit a short user profile
	* @param  ProfileMainRequest $request
	* @return void
	*/
	public function post(ProfileMainRequest $request)
	{
		$user 						= Auth::user();
		$this->userRepository->update($user, $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Edit a full user profile
	* @param ProfileSecondRequest $request
	* @return void
	*/
	public function secondPost(ProfileSecondRequest $request)
	{
		$user 			= Auth::user();
		$this->userRepository->secondUpdate($user, $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Edit a partner profile
	* @param  ProfilePartnerRequest $request
	* @return void
	*/
	public function partnerPost(ProfilePartnerRequest $request)
	{
		$user 			= Auth::user();
		$this->userRepository->partnerUpdate($user, $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
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
		$confirmAction	= route ('registration.edit.photo.delete', $id);
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
		$photo			= $this->photoRepository->getByIdAndUserId($id, $user->user_id);
		$arParams 		= $request->post();
		if ( !empty($arParams['cancel']) ) return redirect()->route ('registration.edit.photo');
		if ( !empty($arParams['confirm']) ) 
		{
			$this->photoRepository->destroyPhoto($photo);
			return redirect()->route('registration.edit.photo')->with('success','Информация сохранена.');
		}
	}

	/**
	* Show a user diary page
	* @return \Illuminate\Http\Response
	*/
	public function diary()
	{
		$user 			= Auth::user();
		$diaries 		= $this->diaryRepository->getByUser(self::$countPerPage, $user->user_id);
		return response()->view('registration.diary',
		[
			'diaries' 		=> $diaries
		]);
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
		$user 			= Auth::user();
		$this->userRepository->settingUpdate($user, $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Show a top100 page
	* @return \Illuminate\Http\Response
	*/
	public function top100(FormatService $format)
	{
		return response()->view ('registration.top100',
		[
			'textTop100'	=> $format->getTextTop100(),
			'formToTop'		=> $format->getFormToTop()
		]);
	}

	/**
	* update top100
	* @return void
	*/
	public function top100Post(FormatService $format)
	{
		$user 			= Auth::user();
		if (count ($user->photo) == 0)
		{
			return redirect()->route('registration.top100')->with(
				[
						'textTop100'	=> $format->getTextTop100Update(),
						'formToTop'		=> $format->getFormToTopUpdate()
				]
			);
		}
		$this->userRepository->update($user, ['user_top100' => time()]);
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* logout
	* @return void
	*/
	public function logout ()
	{
		$user 			= Auth::user();
		Auth::logout();
		return redirect()->route('home');
	}

	/**
	* login
	* @param Request $request
	* @return void
	*/
	public function login (Request $request)
	{
		$arParams 		= $request->post();
		$username 		= !empty($arParams['username_template'])	? trim($arParams['username_template']) 	: '';
		$password 		= !empty($arParams['pass_template']) 		? $arParams['pass_template'] 			: '';
		$remember 		= true;

		$user 			= $this->userRepository->getByLoginAndPass($username, $password);
		if (empty($user)) 
		{
			$title 			= 'Информация';
			$text			= 'Неверно указаны имя пользователя или пароль!';
			Helper::outMessageDie($title, $text);
		} else
			Auth::login($user, $remember);
		return redirect()->route('home');
	}

	/**
	* show a forget password page
	* @return \Illuminate\Http\Response
	*/
	public function forgetPass ()
	{
		return response()->view ('registration.forget_pass');
	}

	/**
	* send email from the forget password page
	* @param  ForgetPasswordRequest $request
	* @return void
	*/
	public function forgetPassPost (ForgetPasswordRequest $request)
	{
		$arParams 		= $request->post();
		$email	= $arParams['mail'];
		$user	= $this->userRepository->getByEmail($email);

		if (!empty($user))
		{
			$oMail 					= new \stdClass();
			$oMail->emailTo 		= $email;
			$oMail->emailFrom 		= config('mail.email_main');
			$oMail->template 		= 'mails.pass';
			$oMail->templateText 	= 'mails.txt.pass';
			$oMail->login 			= $user->user_login;
			$oMail->password		= $user->user_password;
			$oMail->sitename 		= '<a href="' . self::$siteUrlWithProtocol . '">' . self::$siteUrl . '</a>';
			$oMail->sitenameNoTags	= self::$siteUrl;
			$oMail->subject			= "Запрос пароля на www.avioletta.ru";
			Mail::mailer(config('mail.mail_mode'))
        	->to($oMail->emailTo)
        	->send(new Email($oMail));
		}
		return redirect()->route(Route::currentRouteName())->with('success','<p>На адрес <strong>' . $email . '</strong> было выслано письмо с вашим паролем!');
	}

	/**
	* show a registration page
	* @return \Illuminate\Http\Response
	*/
	public function registration ()
	{
		if (session('success')) return response()->view ('registration.finish');
		$user 			= Auth::user();
		if (!empty ($user)) return redirect()->route('home');

		$days 		= Helper::getDays();
		$months 	= Helper::getMonths();
		$years 		= Helper::getYears();
		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old ('country');
		$regionId	= (int) old ('region');
		$regions 	= $countryId > 0 	? $this->regionRepository->getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: [];

		return response()->view ('registration.registration',
		[
			'days'			=> $days,
			'months'		=> $months,
			'years'			=> $years,
			'countries'		=> $countries,
			'regions'		=> $regions,
			'cities'		=> $cities
		]);
	}

	/**
	 * post data after registration and send e-mail with registration information
     * @param  RegistrationRequest $request
	 * @return void
	 */
	public function registrationPost (RegistrationRequest $request)
	{
		$arParams 				= $request->post();
		$ip 					= $request->ip();
		$login 					= !empty ($arParams ['login']) 				? $arParams['login'] 				: '';
		$password 				= !empty ($arParams ['password'])			? $arParams['password'] 			: '';
		$city 					= !empty ($arParams ['city']) 				? $arParams['city'] 				: 0;
		$region 				= !empty ($arParams ['region']) 			? $arParams['region'] 				: 0;
		$country 				= !empty ($arParams ['country']) 			? $arParams['country'] 				: 0;
		$code = md5 (time() . $login . rand(0, 1000));
		$aFields = [
			'user_active' 				=> 1,
			'user_odobreno' 			=> 1,
			'user_login' 				=> $login,
			'user_password' 			=> $password,
			'user_hash'	 				=> md5($password),
			'user_mail' 				=> $arParams['mail'],
			'user_sex' 					=> $arParams['sex'],
			'user_name' 				=> $arParams['name'],
			'user_birth_date'	 		=> Helper::getDateStr($arParams['birth_day'],$arParams['birth_month'],$arParams['birth_year']),
			'user_country' 				=> $country,
			'user_region' 				=> $region,
			'user_city'					=> $city,
			'user_make_date'	 		=> date("Y-m-d"),
			'user_make_date_t' 			=> time(),
			'user_refresh_date' 		=> date("Y-m-d"),
			'user_refresh_date_t'	 	=> time(),
			'user_session_time' 		=> time(),
			'user_lastvisit'	 		=> time(),
			'user_ip' 					=> $ip,
			'user_submit_code' 			=> $code,
			'user_description' 			=> "", 
			'user_partner_description' 	=> "",
			'user_confirm_email' 		=> 0
		];

		$this->userRepository->create($aFields);
		$userId	= $this->userRepository->getId();

		$oMail 					= new \stdClass();
		$oMail->emailTo 		= $arParams['mail'];
		$oMail->emailFrom 		= config('mail.email_main');
		$oMail->template 		= 'mails.register';
		$oMail->templateText 	= 'mails.txt.register';
		$oMail->login 			= $login;
		$oMail->password		= $password;
		$oMail->id				= $userId;
		$oMail->code			= $code;
		$oMail->sitename 		= '<a href="' . self::$siteUrlWithProtocol . '">' . self::$siteUrl . '</a>';
		$oMail->sitenameNoTags	= self::$siteUrl;
		$oMail->subject			= "Регистрация на www.avioletta.ru";
		Mail::mailer(config('mail.mail_mode'))
		->to($oMail->emailTo)
		->send(new Email($oMail));

		$user 			= $this->userRepository->getByLoginAndPass($login, $password);
		Auth::login($user, true);

		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	 * show confirm registration page and update data
     * @param int $id
     * @param string $code
	 * @return \Illuminate\Http\Response
	 */
	public function confirm ($id, $code)
	{
		if (empty ($code)) abort(404);
		$user 			= $this->userRepository->getByIdAndConfirmCode($id, $code);
		$isConfirmed 	= false;
		if (!empty ($user))
		{
			$user->user_confirm_email 	= 1;
			$user->user_submit_code 	= '';
			$user->update();
			$isConfirmed = true;
		}
		return response()->view ('registration.confirm',
		[
			'isConfirmed'			=> $isConfirmed,
		]);
	}
}