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
use App\Interfaces\DiaryInterface;
use App\Interfaces\PhotoInterface;
use Validator;
use App\Helpers\Helper;
use App\Models\Region;
use App\Models\User;
use App\Models\Photo;
use App\Models\AnketVisit;
use App\Mail\Email;

class RegistrationController extends Controller
{
	public static $siteUrl				= 'www.avioletta.ru';
	public static $siteUrlWithProtocol	= 'http://www.avioletta.ru';

	public static $rulesEdit = [
		'name'	=> ['required', 'max:30', 'min:2', 'birth_data', 'birth_data_correct'],
		'sex'	=> ['required'],
		'city'	=> ['place_empty', 'place_correct']
	];

	public static $rulesRegistration = [
		'login'					=> ['required', 'check_ban','max:20', 'min:4', "regex:/^[0-9a-zA-Z_]+$/", 'check_login'],
		'password'				=> ['required', 'max:20', 'min:4', "regex:/^[0-9a-zA-Z_]+$/", 'check_password'],
		'name'					=> ['required', 'max:30', 'min:2', 'birth_data', 'birth_data_correct'],
		'sex'					=> ['required'],
		'mail'					=> ['required', "regex:/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}|museum$/i", "check_email"],
		'country'				=> ['place_empty', 'place_correct'],
		'recaptcha_response' 	=> ['required', 'capcha'],
		'conditions'			=> ['required'],
	];

	public static $rulesPartnerEdit = [
		'partner_age_min' 		=> ['age_valid'],
		'partner_weight_min' 	=> ['weight_valid'],
		'partner_height_min' 	=> ['height_valid'],
	];

	public static $rulesPhoto = [
		'photo_link'	=> ['required','file', 'image', 'max:4048'],
	];

	public static $rulesPass = [
		'pass_old'	=> ['required', 'pass_not_correct', 'pass_not_match'],
		'pass'		=> ['required', 'max:15', 'min:5'],
	];

	public static $rulesForgetPass = [
		'mail'		=> ['required', 'email']
	];

	public static $errMessagesForgetPass= [
		'mail.required'			=> 'не указан Е-майл',
		'mail.email'			=> 'указан некорректный Е-майл'
	];

	public static $errMessagesEdit = [
		'name.required'		 	=> 'Имя не заполнено',
		'name.max'		 		=> 'Имя слишком длинное',
		'name.min'		 		=> 'Имя слишком короткое',
		'sex.required'		 	=> 'Вы не указали пол',
		'birth_data'			=> 'Не указана дата рождения',
		'birth_data_correct'	=> 'Некорректная дата рождения',
		'place_empty'			=> 'Не указано место жительства',
		'place_correct'			=> 'Неверно указано место жительства'
	];

	public static $errMessagesPass = [
		'pass_old.required'		=> 'Старый пароль не заполнен',
		'pass_not_correct'		=> 'Старый пароль указан не верно',
		'pass_not_match'		=> 'Новые пароли не совпадают',
		'pass.required'			=> 'Новый пароль не заполнен',
		'pass.max'		 		=> 'Новый пароль слишком длинный',
		'pass.min'		 		=> 'Новый пароль слишком короткий',
	];

	public static $errMessagesRegistration = [
		'login.required'				=> 'Логин не заполнен',
		'login.check_ban'				=> 'Вы забанены из-за нарушения правил нашего сайта, по всем вопросам обращайтесь к администрации сайта',
		'login.min'						=> 'Логин меньше :min символов',
		'login.max'						=> 'Логин больше :max символов',
		'login.regex'					=> 'При заполнении логина допускается использовать только цифры, буквы латинского алфавита и нижнее подчеркивание',
		'login.check_login'				=> 'Пользователь с таким логином уже существует, выберите другой логин',
		'password.required'				=> 'Пароль не заполнен',
		'password.min'					=> 'Пароль меньше :min символов',
		'password.regex'				=> 'При заполнении пароля допускается использовать только цифры, буквы латинского алфавита и нижнее подчеркивание',
		'password.check_password'		=> 'Введенные пароли не совпадают',
		'name.required'		 			=> 'Имя не заполнено',
		'name.max'		 				=> 'Имя больше :max символов',
		'name.min'		 				=> 'Имя меньше :min символов',
		'sex.required'		 			=> 'Вы не указали пол',
		'birth_data'					=> 'Не указана дата рождения',
		'birth_data_correct'			=> 'Некорректная дата рождения',
		'mail.required'		 			=> 'Не указан Е-майл',
		'mail.regex'			 		=> 'Указан некорректный Е-майл',
		'mail.check_email'		 		=> 'Пользователь с таким Е-майл уже зарегистрирован',
		'place_empty'					=> 'Не указано место жительства',
		'place_correct'					=> 'Неверно указано место жительства',
		'recaptcha_response.required'	=> 'Капча не пройдена',
		'recaptcha_response.capcha'		=> 'Капча не пройдена',
		'conditions.required'			=> 'Пожалуйста, согласитесь с нашими условиями'
	];

	public static $errMessagesPartnerEdit = [
		'age_valid' 	=> 'Не верно указан возраст партнера',
		'weight_valid' 	=> 'Не верно указан вес партнера',
		'height_valid' 	=> 'Не верно указан рост партнера'
	];

	public static $errMessagesPhoto = [
		'photo_link.image'		=> 'Файл не является изображением',
		'photo_link.max'		=> 'Файл слишком большой',
		'photo_link.required'	=> 'Файл не был загружен',
	];

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
		protected CountryInterface $countryRepository,
		protected DiaryInterface $diaryRepository,
		protected PhotoInterface $photoRepository,
	)
	{
	}

	/**
	* Show an edit short profile page
	* @return \Illuminate\Http\Response
	*/
	public function edit ()
	{
		$user 		= Auth::user();
		$days 		= Helper::getDays();
		$months 	= Helper::getMonths();
		$years 		= Helper::getYears();
		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old ('country', $user->user_country);
		$regionId	= (int) old ('region', $user->user_region);
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: [];

		return response()->view ('registration.edit',
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
	public function second ()
	{
		$user 			= Auth::user();
		$sexOrient 		= Helper::BlockSelect('sex_orient',SEX_ORIENT_CLASS,$user->user_sex_orient,2);
		$meetTarget 	= Helper::BlockSelect('meet_target',MEET_TARGET_CLASS,$user->meet_target,2);
		$speakLang 	 	= $user->speak_lang;
		$userSpeakLang	= [];
		
		if (is_array($speakLang))
		{

			foreach (self::$languageCodes as $k => $v)
			{
				if (in_array($k, $speakLang)) $userSpeakLang[$v]['selected'] = 1;
			}
		}

		$body 			= Helper::BlockSelect("body",BODY_CLASS,$user->user_body,2);
		$heights 		= Helper::getHeights();
		$weights 		= Helper::getWeights();
		$hairColor 		= Helper::BlockSelect("hair_color",HAIR_COLOR_CLASS,$user->user_hair_color,2);
		$hairType 		= Helper::BlockSelect("hair_type",HAIR_TYPE_CLASS,$user->user_hair_type,2);
		$eyes	 		= Helper::BlockSelect("eyes",EYES_CLASS,$user->user_eyes,2);
		$education 		= Helper::BlockSelect("education",EDUCATION_CLASS,$user->user_education,2);
		$smoke 			= Helper::BlockSelect("smoke",SMOKE_CLASS,$user->user_smoke,2);
		$spirt			= Helper::BlockSelect("spirt",SPIRT_CLASS,$user->user_spirt,2);
		$familyStatus	= Helper::BlockSelect("family_status",FAMILY_STATUS_CLASS,$user->user_sem_polozh,2);
		$children		= Helper::BlockSelect("children",CHILDREN_CLASS,$user->user_children,2);
		$helpMoney		= Helper::BlockSelect("help_money",HELP_MONEY_CLASS,$user->user_help_money,2);
		$interest		= Helper::BlockSelect("interest",INTEREST_CLASS,$user->interests,2);

		return response()->view ('registration.second',
		[
			'userData'		=> $user,
			'sexOrient'		=> $sexOrient,
			'meetTarget'	=> $meetTarget,
			'userSpeakLang' => $userSpeakLang,
			'body' 			=> $body,
			'heights' 		=> $heights,
			'weights' 		=> $weights,
			'hairColor' 	=> $hairColor,
			'hairType' 		=> $hairType,
			'eyes' 			=> $eyes,
			'education' 	=> $education,
			'smoke' 		=> $smoke,
			'spirt' 		=> $spirt,
			'familyStatus' 	=> $familyStatus,
			'children' 		=> $children,
			'helpMoney' 	=> $helpMoney,
			'interest' 		=> $interest,
		]);
	}

	/**
	* Show an edit partner page
	* @return \Illuminate\Http\Response
	*/
	public function partner ()
	{
		$user 				= Auth::user();
		$age 				= Helper::getAges();
		$heights 			= Helper::getHeights();
		$weights 			= Helper::getWeights();
		$partnerBody		= Helper::BlockSelect("partner_body[]",BODY_CLASS, old ('partner_body', $user->user_partner_body),2);
		$partnerSpeakLang	= Helper::BlockSelect("partner_speak_lang[]",SPEAK_LANG_CLASS, old ('partner_speak_lang', $user->user_partner_speak_lang),2);
		$partnerSpirt		= Helper::BlockSelect("partner_spirt[]",SPIRT_CLASS, old ('partner_spirt', $user->user_partner_spirt),2);
		$partnerSmoke		= Helper::BlockSelect("partner_smoke[]",SMOKE_CLASS, old ('partner_smoke', $user->user_partner_smoke),2);
		$partnerEducation	= Helper::BlockSelect("partner_education[]",EDUCATION_CLASS, old ('partner_education', $user->user_partner_education),2);

		$countries	= $this->countryRepository->getAll();
		$countryId	= (int) old ('country', $user->user_partner_country);
		$regionId	= (int) old ('region', $user->user_partner_region);
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? $this->cityRepository->getByRegionId($regionId) 		: [];

		return response()->view ('registration.partner',
		[
			'userData'			=> $user,
			'age'				=> $age,
			'heights' 			=> $heights,
			'weights' 			=> $weights,
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
	public function photo ()
	{
		$user = User::with('photo')->find(Auth::id());
		return response()->view ('registration.photo',
		[
			'photos' => $user->photo
		]);
	}

	/**
	* Show a change password page
	* @return \Illuminate\Http\Response
	*/
	public function pass ()
	{
		return response()->view ('registration.pass');
	}

	/**
	* Show a delete profile page
	* @return \Illuminate\Http\Response
	*/
	public function delete ()
	{
		return response()->view ('registration.delete');
	}

	/**
	* Delete profile
	* @return void
	*/
	public function deleteConfirm ()
	{
		$user = Auth::user();
		if (count ($user->photo) > 0)
		{
			foreach ($user->photo as $item) {
				helper::delPhoto ($item);
			}
		}

		$visits = AnketVisit::select('*')->where ('ank_user_id', $user->user_id)->get();
		foreach ($visits as $item)
		{
			$item->delete();
		}
		$user->delete();

		return redirect()->route('registration.delete')->with('success','Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.');
	}

	/**
	* Change user password
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function passPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		Validator::extend('pass_not_correct',
		function () use ($arParams, $user) {
			return ($arParams['pass_old'] != $user->user_password ) ? false : true;
		});

		Validator::extend('pass_not_match',
		function () use ($arParams) {
			return ($arParams['pass'] != $arParams['pass_confirm'] ) ? false : true;
		});

		$validator = Validator::make($arParams, self::$rulesPass, self::$errMessagesPass);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;
			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

		$user->user_password 		= $arParams['pass'];
		$user->user_hash 			= md5($arParams['pass']);
		$user->user_session_time	= time();
		$user->user_lastvisit 		= time();
		$user->update();
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Show an edit picture page
	* @param int $id
	* @return \Illuminate\Http\Response
	*/
	public function editPhoto ($id)
	{
		$user 	= User::with('photo')->find(Auth::id());
		$photo 	= [];
		if (count($user->photo))
		{
			foreach ($user->photo as $item)
			{
				if ($item->fotos_id == $id && $item->user_id == $user->user_id)
				{
					$photo = $item;
					break;
				}
			}
		}

		return response()->view ('registration.photo_edit',
		[
			'photo' => $photo
		]);
	}

	/**
	* Reupload an user picture
	* @param  \Illuminate\Http\Request  $request
	* @param int $id
	* @return void
	*/
	public function editPhotoPost (Request $request, $id)
	{
		$user 			= User::with('photo')->find(Auth::id());
		$photo 			= $this->photoRepository->getById($id);
		$photoId		= $id;
		$arParams 		= $request->post();
		$files 			= $request->file();
		$arParams		= array_merge($arParams, $files);

		$validator = Validator::make($arParams, self::$rulesPhoto, self::$errMessagesPhoto);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;
			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}
		
		if (empty ($photo) || $photo->user_id != $user->user_id)
		{
			$title 			= 'Информация';
			$text			= 'Вы можете удалять только свои фотографии<br /><br />';
			$text			.= '<a class="name" href="' . route ('registration.edit') . '">Перейти в Мой профиль</a><br /><br />';
			$text			.= '<a class="name" href="' . route ('ank.id', $user->user_id) . '">Перейти в Мою анкету</a>';
			Helper::outMessageDie($title, $text);
		}

		if (!empty($arParams['photo_link']))
		{
			$photo->fotos_t 	= time();
			$photo->update();
			$extension 								= $arParams['photo_link']->extension();
			$arParams['photo_link']->nameForInsert 	= $photoId . '.' . $extension;
			$picture = Helper::fotoUpload($arParams['photo_link'], 1000, 'fotos_new/');

			$user->user_refresh_date 	= date("Y-m-d");
			$user->user_refresh_date_t 	= time();
			$user->user_session_time 	= time();
			$user->user_lastvisit 		= time();
			$user->update();
			return redirect()->back()
			->with('success','Фото успешно добавлено')
			->withInput();
		}
	}

	/**
	* Add an user picture
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function photoPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();
		$files 			= $request->file();
		$arParams		= array_merge($arParams, $files);

		$validator = Validator::make($arParams, self::$rulesPhoto, self::$errMessagesPhoto);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;
			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

		if (!empty($arParams['photo_link']))
		{
			$user->user_refresh_date 	= date("Y-m-d");
			$countPhoto 				= count($user->photo);
			$photoPortret 				= $countPhoto > 0 ? 0 : 1;
			$aFields = [
				'fotos_portret'				=> $photoPortret,
				'fotos_t'					=> 0,
				'user_id'					=> $user->user_id
			];
	
			$oPhoto 								= new Photo ($aFields);
			$oPhoto->save();
			$photoId								= $oPhoto->getKey();
			$extension 								= $arParams['photo_link']->extension();
			$arParams['photo_link']->nameForInsert 	= $photoId . '.' . $extension;
			
			$picture = Helper::fotoUpload($arParams['photo_link'], 1000, 'fotos_new/');
			$countPhoto++;
			$countPhoto = $countPhoto > 5 ? 5 : $countPhoto;
			
			$user->user_fotos 			= $countPhoto;
			$user->user_refresh_date 	= date("Y-m-d");
			$user->user_refresh_date_t 	= time();
			$user->user_session_time 	= time();
			$user->user_lastvisit 		= time();
			$user->update();
			return redirect()->back()
			->with('success','Фото успешно добавлено')
			->withInput();
		}
	}

	/**
	* Edit a short user profile
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function editPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		Validator::extend('birth_data',
		function () use ($arParams) {
			return ((int)$arParams['birth_day'] == 0 || (int)$arParams['birth_month'] == 0 || (int)$arParams['birth_year'] == 1900 || (int)$arParams['birth_year'] == 0) ? false : true;
		});

		Validator::extend('birth_data_correct',
		function () use ($arParams) {
			$birth_month 	= (int)$arParams['birth_month'];
			$birth_day		= (int)$arParams['birth_day'];
			return (($birth_month == "2" && $birth_day > "29") || (($birth_month == "4" || $birth_month == "6" || $birth_month == "9" || $birth_month == "11") && $birth_day > "30")) ? false : true;
		});

		Validator::extend('place_empty',
		function () use ($arParams) {
			return (int)$arParams['city'] == 0 && (int)$arParams['region'] == 0 && (int)$arParams['country'] == 0 ? false : true;
		});

		Validator::extend('place_correct',
		function () use ($arParams) {
			if ((int)$arParams['city'] == 0 && (int)$arParams['region'] == 0 && (int)$arParams['country'] == 0) return true;
			return (int)$arParams['city'] == 0 || (int)$arParams['region'] == 0 || (int)$arParams['country'] == 0 ? false : true;
		});

		$validator 		= Validator::make($arParams, self::$rulesEdit, self::$errMessagesEdit);

		if ($validator->fails()) {

			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
				->withErrors($strError, 'comment')
				->withInput();
		}

		$user->user_sex 			= $arParams['sex'];
		$user->user_name 			= str_replace("\'", "''", $arParams['name']);
		$user->user_birth_date 		= Helper::getDateStr($arParams['birth_day'],$arParams['birth_month'],$arParams['birth_year']);
		$user->user_country 		= (int)$arParams['country'];
		$user->user_region 			= (int)$arParams['region'];
		$user->user_city 			= (int)$arParams['city'];
		$user->user_refresh_date 	= date("Y-m-d");
		$user->user_refresh_date_t 	= time();
		$user->user_session_time 	= time();
		$user->user_lastvisit 		= time();
		$user->update();

		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Edit a full user profile
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function secondPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		$user->user_sex_orient 		= $arParams['sex_orient'];
		$user->user_sex_orient 		= $user->user_sex_orient < 1 || $user->user_sex_orient > 4 ? 2 : $user->user_sex_orient;
		$user->user_target_meet 	= Helper::serializeInput($arParams['target_meet']);
		$user->user_speak_lang 		= Helper::serializeInput($arParams['speak_lang']);
		$user->user_body 			= (int)$arParams['body'];
		$user->user_height 			= (int)$arParams['height'] < 150 	? 149 	: (int)$arParams['height'];
		$user->user_weight 			= (int)$arParams['weight'] < 30 	? 29 	: (int)$arParams['weight'];
		$user->user_hair_color		= (int)$arParams['hair_color'];
		$user->user_hair_type		= (int)$arParams['hair_type'];
		$user->user_eyes			= (int)$arParams['eyes'];
		$user->user_education		= (int)$arParams['education'];
		$user->user_smoke			= (int)$arParams['smoke'];
		$user->user_spirt			= (int)$arParams['spirt'];
		$user->user_sem_polozh		= (int)$arParams['family_status'];
		$user->user_children		= (int)$arParams['children'];
		$user->user_help_money		= (int)$arParams['help_money'];
		$user->user_interests		= Helper::serializeInput($arParams['interest']);
		$user->user_icq				= (int)$arParams['icq'];
		$user->user_url				= addslashes($arParams['url']);
		$user->user_phone			= addslashes($arParams['phone']);
		$user->user_description		= addslashes($arParams['description']);
		$user->user_refresh_date	= date("Y-m-d");
		$user->user_refresh_date_t 	= time();
		$user->user_session_time 	= time();
		$user->user_lastvisit 		= time();
		$user->user_odobreno		= !empty($user->user_description) ? 0 : $user->user_odobreno;
		
		$user->update();
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Edit a partner profile
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function partnerPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		Validator::extend('age_valid',
		function () use ($arParams) {
			return (int)$arParams['partner_age_min'] > 15 && (int)$arParams['partner_age_max'] > 15 && (int)$arParams['partner_age_min'] <= (int)$arParams['partner_age_max'] ? true : false;
		});

		Validator::extend('height_valid',
		function () use ($arParams) {
			return (int)$arParams['partner_height_min'] > 149 && (int)$arParams['partner_height_max'] > 149 && (int)$arParams['partner_height_min'] <= (int)$arParams['partner_height_max'] ? true : false;
		});

		Validator::extend('weight_valid',
		function () use ($arParams) {
			return (int)$arParams['partner_weight_min'] > 29 && (int)$arParams['partner_weight_max'] > 29 && (int)$arParams['partner_weight_min'] <= (int)$arParams['partner_weight_max'] ? true : false;
		});

		$validator 		= Validator::make($arParams, self::$rulesPartnerEdit, self::$errMessagesPartnerEdit);

		if ($validator->fails()) {

			$messages = $validator->messages();
			$messages->has('partner_age_min') 		? $messages->add('age', $messages->get('partner_age_min')[0]) 		: '';
			$messages->has('partner_weight_min') 	? $messages->add('age', $messages->get('partner_weight_min')[0]) 	: '';
			$messages->has('partner_height_min') 	? $messages->add('age', $messages->get('partner_height_min')[0]) 	: '';

			$strError = $messages;

			return redirect()->back()
				->withErrors($strError, 'comment')
				->withInput();
		}

		$user->user_partner_age_min 			= (int)$arParams['partner_age_min'];
		$user->user_partner_age_max 			= (int)$arParams['partner_age_max'];
		$user->user_partner_height_min 			= (int)$arParams['partner_height_min'];
		$user->user_partner_height_max 			= (int)$arParams['partner_height_max'];
		$user->user_partner_weight_min 			= (int)$arParams['partner_weight_min'];
		$user->user_partner_weight_max 			= (int)$arParams['partner_weight_max'];
		$user->user_partner_body				= Helper::serializeInput($arParams['partner_body']);
		$user->user_partner_speak_lang			= Helper::serializeInput($arParams['partner_speak_lang']);
		$user->user_partner_spirt				= Helper::serializeInput($arParams['partner_spirt']);
		$user->user_partner_smoke				= Helper::serializeInput($arParams['partner_smoke']);
		$user->user_partner_education			= Helper::serializeInput($arParams['partner_education']);
		$user->user_partner_country 			= (int)$arParams['country'];
		$user->user_partner_region	 			= (int)$arParams['region'];
		$user->user_partner_city	 			= (int)$arParams['city'];
		$user->user_partner_description			= addslashes($arParams['description']);
		$user->user_refresh_date 				= date("Y-m-d");
		$user->user_refresh_date_t 				= time();
		$user->user_session_time 				= time();
		$user->user_lastvisit 					= time();
		$user->user_odobreno					= !empty($user->user_partner_description) ? 0 : $user->user_odobreno;
		$user->update();

		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Delete an user picture
	* @param  \Illuminate\Http\Request  $request
	* @param int $id
	* @return void
	*/
	public function deletePhoto(Request $request, $id)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		if ( !empty($arParams['cancel']) ) 
		{
			return redirect()->route ('registration.edit.photo');
		}

		if ( !empty($arParams['confirm']) ) 
		{
			$photo = $this->photoRepository->getById($id);
			if (empty ($photo) || $photo->user_id != $user->user_id)
			{
				$title 			= 'Информация';
				$text			= 'Вы можете удалять только свои фотографии<br /><br />';
				$text			.= '<a class="name" href="' . route ('registration.edit') . '">Перейти в Мой профиль</a><br /><br />';
				$text			.= '<a class="name" href="' . route ('ank.id', $user->user_id) . '">Перейти в Мою анкету</a>';
				Helper::outMessageDie($title, $text);
			}

			if (file_exists("fotos_new/".$id.".jpg")) {
				if(unlink("fotos_new/".$id.".jpg")) {}
			}

			$isPortret = $photo->fotos_portret == 1 ? 1 : 0;

			$photo->delete();

			if ($isPortret)
			{
				$photo = $this->photoRepository->getFirstByUserId($user->user_id);

				if (!empty($photo))
				{
					$photo->fotos_portret = 1;
					$photo->update();
				}
			}

			$user->user_refresh_date 	= date("Y-m-d");
			$user->user_refresh_date_t 	= time();
			$user->user_session_time 	= time();
			$user->user_lastvisit 		= time();
			$user->user_fotos 			= count ($this->photoRepository->getAllByUserId($user->user_id));
			$user->update();

			return redirect()->route('registration.edit.photo')->with('success','Информация сохранена.');
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction 	= route ('registration.edit.photo.delete', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
	* Show a user diary page
	* @return \Illuminate\Http\Response
	*/
	public function diary ()
	{
		$user 			= Auth::user();
		$userId			= $user->user_id;
		$diaries 		= $this->diaryRepository->getByUser (self::$countPerPage, $userId);
		$page			= $diaries->currentPage();
		$pagination		= Helper::preparePagination ($diaries->toArray()['links']);

		return response()->view ('registration.diary',
		[
			'diaries' 		=> $diaries,
			'pagination'	=> $pagination,
		]);
	}

	/**
	* Show a setting page
	* @return \Illuminate\Http\Response
	*/
	public function settings ()
	{
		return response()->view ('registration.settings');
	}

	/**
	* Update user settings
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function settingsPost (Request $request)
	{
		$user 			= Auth::user();
		$arParams 		= $request->post();

		$user->dont_send_email		= !empty ($arParams['dont_send_email']) ? (int) $arParams['dont_send_email'] : 0;
		$user->user_refresh_date	= date("Y-m-d");
		$user->user_refresh_date_t	= time();
		$user->user_session_time	= time();
		$user->user_lastvisit		= time();		
		$user->update();
		return redirect()->route(Route::currentRouteName())->with('success','Информация сохранена.');
	}

	/**
	* Show a top100 page
	* @return \Illuminate\Http\Response
	*/
	public function top100 ()
	{
		$user 			= Auth::user();
		
		$textTop100 = '<p>Чтобы поднять анкету в ТОПе нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
					<p>1. Иметь регистрацию на нашем сайте;</p>
					<p>2. У вас должна быть загружена хотя бы одна фотография;</p>
					<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
					<p><br /></p>';

		$formToTop 	= '<form name="anketa" action="' . route ('registration.top100.post') . '" method="post">' . 
						csrf_field() . '
						<center>
						<input type="submit" name="otsil" value="Поднять анкету" />
						</center>
					</form>';

		$textTop100	= session('textTop100') ?: $textTop100;
		$formToTop	= session('formToTop') 	?: $formToTop;


		return response()->view ('registration.top100',
		[
			'textTop100'	=> !empty ($textTop100) ? $textTop100 	: '',
			'formToTop'		=> !empty ($formToTop)	? $formToTop	: ''
		]);
	}

	/**
	* update top100
	* @return void
	*/
	public function top100Post ()
	{
		$user 			= Auth::user();
		if (count ($user->photo) == 0)
		{
			$textTop100 = '<p style="color:#f00">Вы не можете попасть в ТОП, т.к. не выполнено одно из условий</p>
						<p>Чтобы стать участником ТОПа нашего сайта, Вам необходимо выполнить <strong>всего 3 условия:</strong></p>
						<p>1. Иметь регистрацию на нашем сайте;</p>
						<p style="color:#f00">2. У вас должна быть загружена хотя бы одна фотография;</p>
						<p>3. Вам необходимо подтвердить желание участвовать в ТОПе.</p>
						<p><strong>Перейти в раздел <a class="name" href="' . route ('registration.edit.photo') . '">Мои фото</a></strong></p>
						<p><br></p>';
			$formToTop = '<form name="anketa" action="' . route ('registration.top100.post') . '" method="post">' .
						csrf_field() . '
						<input type="submit" name="otsil" value="Попасть в ТОП" /></form>';

			return redirect()->route('registration.top100')->with(
				[
						'textTop100'	=> !empty ($textTop100) ? $textTop100 		: '',
						'formToTop'		=> !empty ($formToTop)	? $formToTop		: ''
				]
			);
		}

		$user->user_top100			= time();
		$user->update();

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
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function login (Request $request)
	{
		$arParams 		= $request->post();
		$username 		= !empty($arParams['username_template'])	? trim($arParams['username_template']) 	: '';
		$password 		= !empty($arParams['pass_template']) 		? $arParams['pass_template'] 			: '';
		$remember 		= true;

		$user 			= User::getByLoginAndPass($username, $password);
		if (empty($user)) 
		{
			$title 			= 'Информация';
			$text			= 'Неверно указаны имя пользователя или пароль!';
			Helper::outMessageDie($title, $text);
		}

		Auth::login($user);
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
	* @param  \Illuminate\Http\Request  $request
	* @return void
	*/
	public function forgetPassPost (Request $request)
	{
		$arParams 		= $request->post();
		$validator 		= Validator::make($arParams, self::$rulesForgetPass, self::$errMessagesForgetPass);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
				->withErrors($strError, 'comment')
				->withInput();
		}
		$email 	= $arParams['mail'];
		$user 	= User::getByEmail($email);

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
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
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
     * @param  \Illuminate\Http\Request  $request
	 * @return void
	 */
	public function registrationPost (Request $request)
	{
		$arParams 				= $request->post();
		$ip 					= $request->ip();
		$ban 					= $this->banListRepository->getByIP($ip);
		$login 					= !empty ($arParams ['login']) 				? $arParams['login'] 				: '';
		$password 				= !empty ($arParams ['password'])			? $arParams['password'] 			: '';
		$password_second 		= !empty ($arParams ['password_second'])	? $arParams['password_second']		: '';
		$city 					= !empty ($arParams ['city']) 				? $arParams['city'] 				: 0;
		$region 				= !empty ($arParams ['region']) 			? $arParams['region'] 				: 0;
		$country 				= !empty ($arParams ['country']) 			? $arParams['country'] 				: 0;
		$recaptcha_response  	= !empty ($arParams['recaptcha_response']) 	? $arParams['recaptcha_response'] 	: '';

		Validator::extend('check_ban',
		function () use ($ban) {
			return empty($ban) ? true : false;
		});

		Validator::extend('check_login',
		function () use ($login) {
			return empty(User::getByLogin($login)) ? true : false;
		});

		Validator::extend('check_password',
		function () use ($password, $password_second) {
			return $password == $password_second ? true : false;
		});

		Validator::extend('birth_data',
		function () use ($arParams) {
			return ((int)$arParams['birth_day'] == 0 || (int)$arParams['birth_month'] == 0 || (int)$arParams['birth_year'] == 1900 || (int)$arParams['birth_year'] == 0) ? false : true;
		});

		Validator::extend('birth_data_correct',
		function () use ($arParams) {
			$birth_month 	= (int)$arParams['birth_month'];
			$birth_day		= (int)$arParams['birth_day'];
			return (($birth_month == "2" && $birth_day > "29") || (($birth_month == "4" || $birth_month == "6" || $birth_month == "9" || $birth_month == "11") && $birth_day > "30")) ? false : true;
		});

		Validator::extend('check_email',
		function () use ($arParams) {
			return empty(User::getByEmail($arParams['mail'])) ? true : false;
		});

		Validator::extend('place_empty',
		function () use ($city, $region, $country) {
			return (!$city && !$region && !$country) ? false : true;
		});
		Validator::extend('place_correct',
		function () use ($city, $region, $country) {
			if (!$city && !$region && !$country) return true;
			return (!$city || !$region || !$country) ? false : true;
		});


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

		$validator 		= Validator::make($arParams, self::$rulesRegistration, self::$errMessagesRegistration);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;
			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

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
		$oUser = new User($aFields);
		$oUser->save();
		$userId	= $oUser->getKey();

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

		$user 			= User::getByLoginAndPass($login, $password);
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
		$user 			= User::getByIdAndConfirmCode($id, $code);
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