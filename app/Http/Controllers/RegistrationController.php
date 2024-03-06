<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use App\Helpers\Helper;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\User;
use App\Models\Photo;

class RegistrationController extends Controller
{

	public static $rulesEdit = [
		'name'	=> ['required', 'max:30', 'min:2', 'birth_data', 'birth_data_correct'],
		'sex'	=> ['required'],
		'city'	=> ['place_empty', 'place_correct'],
	];

	public static $rulesPartnerEdit = [
		'partner_age_min' 		=> ['age_valid'],
		'partner_weight_min' 	=> ['weight_valid'],
		'partner_height_min' 	=> ['height_valid'],
	];

	public static $rulesPhoto = [
		'photo_link'	=> ['required','file', 'image', 'max:4048'],
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
    }

	public function edit (Request $request)
	{
		$user 		= Auth::user();
		$days 		= Helper::getDays();
		$months 	= Helper::getMonths();
		$years 		= Helper::getYears();
		$countries	= Country::getAll();
		$countryId	= (int) old ('country', $user->user_country);
		$regionId	= (int) old ('region', $user->user_region);
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? City::getByRegionId($regionId) 		: [];

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

	public function second (Request $request)
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

	public function partner (Request $request)
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

		$countries	= Country::getAll();
		$countryId	= (int) old ('country', $user->user_partner_country);
		$regionId	= (int) old ('region', $user->user_partner_region);
		$regions 	= $countryId > 0 	? Region::getByCountryId($countryId) 	: [];
		$cities 	= $regionId	> 0 	? City::getByRegionId($regionId) 		: [];

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

	public function photo (Request $request)
	{
		$user = User::with('photo')->find(Auth::id());
		return response()->view ('registration.photo',
		[
			'photos' => $user->photo
		]);
	}

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
			
			$picture = Helper::fotoUpload($arParams['photo_link'], 0, 'fotos_new/');
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
			$photo = Photo::getById($id);
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
				$photo = Photo::getFirstByUserId($user->user_id);

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
			$user->user_fotos 			= count (Photo::getAllByUserId($user->user_id));
			$user->update();

			return redirect()->route('registration.edit.photo')->with('success','Информация сохранена.');
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction 	= route ('registration.edit.photo.delete', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}
}