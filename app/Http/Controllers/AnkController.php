<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\DiaryInterface;
use App\Interfaces\DiaryCommentInterface;
use App\Interfaces\PhotoInterface;
use App\Interfaces\VarsInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\CommentPhotoInterface;
use App\Requests\PhotoCommentRequest;
use App\Requests\DiaryRequest;
use App\Helpers\Helper;

class AnkController extends Controller
{
	public static $getProps = [
		'\\App\\Repositories\\BodyRepository'			=> ['prop' =>'user_body', 				'ank_prop' =>'body'],
		'\\App\\Repositories\\HairColorRepository'		=> ['prop' =>'user_hair_color', 		'ank_prop' =>'hair_color'],
		'\\App\\Repositories\\HairTypeRepository' 		=> ['prop' =>'user_hair_type', 			'ank_prop' =>'hair_type'],
		'\\App\\Repositories\\EyesRepository' 			=> ['prop' =>'user_eyes',	 			'ank_prop' =>'eyes'],
		'\\App\\Repositories\\FamilyStatusRepository' 	=> ['prop' =>'user_sem_polozh',			'ank_prop' =>'family_status'],
		'\\App\\Repositories\\ChildrenRepository' 		=> ['prop' =>'user_children',			'ank_prop' =>'children'],
		'\\App\\Repositories\\EducationRepository' 		=> ['prop' =>'user_education',			'ank_prop' =>'education'],
		'\\App\\Repositories\\SmokeRepository' 			=> ['prop' =>'user_smoke',				'ank_prop' =>'smoke'],
		'\\App\\Repositories\\SpirtRepository' 			=> ['prop' =>'user_spirt',				'ank_prop' =>'spirt'],
		'\\App\\Repositories\\HelpMoneyRepository' 		=> ['prop' =>'user_help_money',			'ank_prop' =>'help_money'],
		'\\App\\Repositories\\SexOrientRepository' 		=> ['prop' =>'user_sex_oriebt',			'ank_prop' =>'sex_orient'],
		'\\App\\Repositories\\CountryRepository'		=> ['prop' =>'user_partner_country',	'ank_prop' =>'partner_country'],
		'\\App\\Repositories\\RegionRepository'			=> ['prop' =>'user_partner_region',		'ank_prop' =>'partner_region'],
		'\\App\\Repositories\\CityRepository'			=> ['prop' =>'user_partner_city',		'ank_prop' =>'partner_city']
	  ];

	public static $visitDays 			= 30;
	public $commentCountPerPage 		= 100;
	public static $diaryPerPage 		= 10;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected AnketEvaluationInterface $anketEvaluationRepository,
		protected AnketVisitInterface $anketVisitRepository,
		protected DiaryInterface $diaryRepository,
		protected DiaryCommentInterface $diaryCommentRepository,
		protected PhotoInterface $photoRepository,
		protected VarsInterface $varsRepository,
		protected UserInterface $userRepository,
		protected CommentPhotoInterface $commentPhotoRepository
	)
	{
	}

	/**
	* Show a profile page
	* @param  Request  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getAnk (Request $request, $id)
	{
		$user 	= Auth::user();
		$mode 	= Route::currentRouteName() == 'ank.full.id' ? 'full' : '';
		$anket 	= $this->userRepository->getById ($id, $mode);

		$vote 	= isset ($request->golos) ? (int)$request->golos : 0;
		$vote 	= $vote > 5 ? 5 : $vote;

		if (empty ($anket)) abort(404);

		$visits = $this->anketVisitRepository->getVisitsByUserId ($id, self::$visitDays);
		
		$anket->userank_visits_month = !empty ($visits) ? count ($visits) : 0 ;

		//making meet targets
		$anket->getPropertyFew('App\Repositories\MeetTargetRepository',	$anket->user_target_meet, 'target_meet');

		//making interests
		$anket->getPropertyFew('App\Repositories\InterestRepository',	$anket->user_interests, 'interests');
		//making an ankets review and a count of views
		if (!empty ($user))
		{
			$ankVisits = $this->anketVisitRepository->getVisitsByUserId ($id, self::$visitDays, $user->user_id);
			$anket->ankVisits = count($ankVisits);
			if ($anket->ankVisits == 0 && $user->user_id != $id && $user->user_id > 1)
			{
				$this->anketVisitRepository->insertVisit($id);
				$this->anketVisitRepository->removeOld(self::$visitDays);
			} elseif ($anket->ankVisits > 0 && $user->user_id != $id)
				$this->anketVisitRepository->updateVisit($id);

			$affectedRows = $this->anketEvaluationRepository->getEvaluations($user->user_id, $id);
			if (count ($affectedRows) == 0)
			{
				if ($request->has('send_golos') && $vote > 0) 
				{
					if ($user->user_id != $id) 
					{
						$aFields = [
							'user_id'			=> $user->user_id,
							'user_id_ocenka'	=> $id,
							'ball'				=> $vote,
							'time'				=> time()
						];

						$this->anketEvaluationRepository->create($aFields);
						$ankEvaluationed = true;
					}

					$voteSum = $this->anketEvaluationRepository->getSum ($id);
					if ($voteSum > 0)
					{
						$anket = $this->userRepository->getJustById($id);
						$anket->user_reiting = $voteSum;
						$anket->update();
					}
					return redirect()->route(Route::currentRouteName(), $id)->with('success','Спасибо. Ваш голос учтен.');
				}
				
			} else 
			{
				$ankEvaluationed = true;
			}
		}
		
		//making a full anket
		if ($mode == 'full') {
			foreach (self::$getProps as $k => $item)
			{
				$anket->getProperty($item, $k);
			}

			if ($anket->user_sex_orient == "1" || $anket->user_sex_orient == "4") 
			{
				$partnerSex = 'Мужской, Женский';
			} elseif ($anket->user_sex_orient == "2") 
			{
				$partnerSex = $anket->user_sex == MEN ? 'Женский' : 'Мужской';
			} else 
			{
				$partnerSex = $anket->user_sex == WOMEN ? 'Женский' : 'Мужской';
			}
			$anket->user_partner_sex = $partnerSex;
	
			//making languages
			$anket->getPropertyFew('App\Repositories\MeetTargetRepository',	$anket->user_speak_lang, 'speak_lang_str');

			//making a partner age
			if ($anket->user_partner_age_min > PARTNER_AGE_MIN || $anket->user_partner_age_max > PARTNER_AGE_MAX) 
			{
				if ($anket->user_partner_age_min > PARTNER_AGE_MIN && $anket->user_partner_age_max > PARTNER_AGE_MAX) 
				{
					$user_partner_age = ' ' . $anket->user_partner_age_min . '-' . $anket->user_partner_age_max;
					$user_partner_age .= ' ' . Helper::ageType($anket->user_partner_age_max);
				} else if ($anket->user_partner_age_min > PARTNER_AGE_MIN && $anket->user_partner_age_max <= PARTNER_AGE_MAX) 
				{
					$user_partner_age = ' от ' . $anket->user_partner_age_min;
					$user_partner_age .= ' ' . Helper::ageType2($anket->user_partner_age_min);
				} else 
				{
					$user_partner_age = ' до ' . $anket->user_partner_age_max;
					$user_partner_age .= ' ' . Helper::ageType2($anket->user_partner_age_max);
				}
				$anket->partner_age = $user_partner_age;
			}

			//making a partner height
			if ($anket->user_partner_height_min > PARTNER_HEIGHT_MIN || $anket->user_partner_height_max > PARTNER_HEIGHT_MAX) 
			{
				if ($anket->user_partner_height_min > PARTNER_HEIGHT_MIN && $anket->user_partner_height_max > PARTNER_HEIGHT_MAX) 
				{
					$user_partner_height = ' ' . $anket->user_partner_height_min . '-' . $anket->user_partner_height_max . ' см';
				} else if ($anket->user_partner_height_min > PARTNER_HEIGHT_MIN && $anket->user_partner_height_max <= PARTNER_HEIGHT_MAX) 
				{
					$user_partner_height = ' от ' . $anket->user_partner_height_min . ' см';
				} else 
				{
					$user_partner_height = ' до ' . $anket->user_partner_height_max . 'см';
				}
				$anket->partner_height = $user_partner_height;
			}

			//making a partner weight
			if ($anket->user_partner_weight_min > PARTNER_WEIGHT_MIN || $anket->user_partner_weight_max > PARTNER_WEIGHT_MAX) 
			{
				if ($anket->user_partner_weight_min > PARTNER_WEIGHT_MIN && $anket->user_partner_weight_max > PARTNER_WEIGHT_MAX) 
				{
					$user_partner_weight = ' ' . $anket->user_partner_weight_min . '-' . $anket->user_partner_weight_max . ' кг'; 
				} else if ($anket->user_partner_weight_min > PARTNER_WEIGHT_MIN && $anket->user_partner_weight_max <= PARTNER_WEIGHT_MAX) 
				{
					$user_partner_weight = ' от ' . $anket->user_partner_weight_min . ' кг';
				} else 
				{
					$user_partner_weight = ' до ' . $anket->user_partner_weight_max . 'кг';
				}
				$anket->partner_weight = $user_partner_weight;
			}

			//making partner body
			$anket->getPropertyFew('\App\Repositories\BodyRepository',	$anket->user_partner_body, 'partner_body');
			//making partner languages
			$anket->getPropertyFew('App\Repositories\SpeakLangRepository',	$anket->user_partner_speak_lang, 'partner_speak_lang');
			//making partner education
			$anket->getPropertyFew('App\Repositories\EducationRepository',	$anket->user_partner_education, 'partner_education');
			//making partner smoke
			$anket->getPropertyFew('App\Repositories\SmokeRepository',	$anket->user_partner_smoke, 'partner_smoke');
			//making partner spirt
			$anket->getPropertyFew('App\Repositories\SpirtRepository',	$anket->user_partner_spirt, 'partner_spirt');

			$isAboutPartner = $anket->isAboutPartner();
		}

		return response()->view ('ankets.page',
		[
			'userData'			=> $anket,
			'ankEvaluationed' 	=> isset($ankEvaluationed) ? $ankEvaluationed : false,
			'isAboutPartner' 	=> isset ($isAboutPartner) ? $isAboutPartner : false
		]);
	}

	/**
	* Show a page with user pictures
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getPhoto ($id)
	{
		$mode 	= Route::currentRouteName() == 'ank.photo.photo_id' ? 'photo.id' : 'ank.photo';

		if ($mode == 'photo.id')
		{
			$photo = $this->photoRepository->getById ($id);
			if (empty ($photo)) abort (404);
			$id = $photo->user_id;
		}
		$anket	= $this->userRepository->getById ($id);
		if (!count ($anket->photo)) abort (404);
		$user	= Auth::user();
		$user	= !empty($user) ? $user->load(['visits']) : null;
		$vars	= $this->varsRepository->getAll();

		$ankVisits = $this->anketVisitRepository->getVisitsByUserId ($id, self::$visitDays, $user->user_id);
		$anket->ankVisits = count($ankVisits);

		if ($anket->ankVisits == 0 && $user->user_id != $id && $user->user_id > 1) 
		{
			$this->anketVisitRepository->insertVisit($id);
			$this->anketVisitRepository->removeOld(self::$visitDays);
		} elseif ($anket->ankVisits > 0 && $user->user_id != $id)
			$this->anketVisitRepository->updateVisit($id);

		foreach ($anket->photo as &$item)
		{
			$item->comment	= $item->comment->slice(0, $this->commentCountPerPage);
			if ($mode == 'photo.id' && $item->fotos_id == $photo->fotos_id)
			$anket->mainPhoto = $item;
		}
		$anket->mainPhoto 	= !empty ($anket->mainPhoto) ? $anket->mainPhoto : $anket->photo[0];
		$photoId 			= $anket->mainPhoto->fotos_id;
		$imgWidth 			= $vars['max_foto_width_big'];
		$img 				= "./fotos_new/".$photoId.".jpg";

		if (is_file($img)) 
		{
			$size = getimagesize($img);
			$anket->mainPhoto->width	= $size [0] > $imgWidth ? $imgWidth : $size [0];
			$anket->mainPhoto->url		= $img;
		}

		if (!empty ($anket->mainPhoto->comment))
		{
			foreach ($anket->mainPhoto->comment as $k => $_item)
			{
				if (!empty($_item->user))
				{
					$_item->user->user_age 			= Helper::age($_item->user->user_birth_date);
					$_item->user->user_age_type 	= Helper::ageType($_item->user->user_age);
					$_item->user->user_name_class 	= $_item->user->user_sex == MEN ? 'name_man' : 'name_woman';
					$_item->user_photo_id 			= !empty($_item->user->photo[0]) ? $_item->user->photo[0]->fotos_id : 0;
				}
				$_item->add_time = date("d.m.y H:i",$_item->time);
				$_item->comments_description = str_replace("\n", "\n<br />\n", $_item->comments_description);
			}
		}
		return response()->view ('ankets.photo',
		[
			'userData'			=> $anket,
		]);
	}

	/**
	* post pictures comments
	* @param  PhotoCommentRequest  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function postComment (PhotoCommentRequest $request, $id)
	{
		//making sending comment
		$user 			= Auth::user();
		$description 	= $request->has('description') ? $request->description : '';
		
		$aFields = [
			'foto_id'					=> $id,
			'user_id'					=> $user->user_id,
			'comments_description' 		=> str_replace("\'", "''", $description),
			'time'						=> time()
		];
		$this->commentPhotoRepository->create($aFields);

		return redirect()->back()
		->with('success','Сообщение успешно отправлено')
		->withInput();
	}
	
	/**
	* show an user diary page
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getDiary ($id)
	{
		$anket 	= $this->userRepository->getById ($id);
		if (empty ($anket->photo)) abort (404);

		$diaries = $this->diaryRepository->getByUser (self::$diaryPerPage, $id);
		if (count ($diaries) == 0) abort (404);

		$page 				= $diaries->currentPage();
		return response()->view ('ankets.diary',
		[
			'userData'		=> $anket,
			'diaries'		=> $diaries,
			'page'			=> $page
		]);
	}

	/**
	* add an user diary
	* @param  DiaryRequest  $request
	* @return \Illuminate\Http\Response
	*/
	public function addDiary (DiaryRequest $request)
	{
		$user 			= Auth::user();
		if (empty ($user)) abort (404);
		$arParams 		= $request->post();
		$description 	= $request->has('description') ? $request->description : '';
		$files 			= $request->file();
		$arParams		= array_merge($arParams, $files);
		$title			= strip_tags($arParams['title'],"<b><strong><i>");
		$description	= strip_tags($arParams['description'],"<b><strong><i>");

		if (!empty($arParams['photo_link']))
		{
			$picture = Helper::fotoUpload($arParams['photo_link'], 0, 'img/dnevnik/');
		}

		$aFields = [
			'dnevniki_user_id'			=> $user->user_id,
			'dnevniki_title'			=> $title,
			'dnevniki_text'				=> $description,
			'dnevniki_picture'			=> !empty ($picture) ? $picture : "0",
			'dnevniki_time'				=> time()
		];

		$this->diaryRepository->create($aFields);

		return redirect()->back()
		->with('success','Дневник успешно добавлен')
		->withInput();
	}

	/**
	* delete the user diary
	* @param  DiaryRequest  $request
	* @param  int $id
	* @return void
	*/
	public function delDiary (DiaryRequest $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		if (empty ($diary)) abort (404);

		$arParams 		= $request->post();

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('ank.diary.id', $user->user_id);
		}

		if ( !empty($arParams['confirm']) ) {
			if (!empty($diary->dnevniki_picture_url) && file_exists($diary->dnevniki_picture_url))
			{
				unlink($diary->dnevniki_picture_url);
			}
			$diary->comments()->delete();
			$diary->delete();
			return redirect()->route ('ank.diary.id', $user->user_id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить эту запись<br /><br />';
		$confirmAction 	= route ('ank.diary.delete.id', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
	* delete the picture of the diary
	* @param  Illuminate\Http\Request $request
	* @param  int $id
	* @return void
	*/
	public function delDiaryPhoto (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		if (empty ($diary)) abort (404);

		$arParams 		= $request->post();

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('ank.diary.edit.id', $id);
		}

		if ( !empty($arParams['confirm']) ) {
			if (!empty($diary->dnevniki_picture_url) && file_exists($diary->dnevniki_picture_url))
			{
				unlink($diary->dnevniki_picture_url);
			}
			$diary->dnevniki_picture = 0;
			$diary->update();
			return redirect()->route ('ank.diary.edit.id', $id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction 	= route ('ank.diary.delete.photo.id', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
	* show edit diary page and update the diary
	* @param  DiaryRequest  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function editDiary (DiaryRequest $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= $this->diaryRepository->getByUserAndId($id, $user->user_id);
		if (empty ($diary)) abort (404);

		$arParams					= $request->post();
		$files 						= $request->file();

		if (!empty($arParams['otsil']))
		{
			$arParams			= array_merge($arParams, $files);
			$title				= strip_tags($arParams['title'],"<b><strong><i>");
			$description		= strip_tags($arParams['description'],"<b><strong><i>");
	
			if (!empty($arParams['photo_link']))
			{
				$picture = Helper::fotoUpload($arParams['photo_link'], 0, 'img/dnevnik/');
			}
	
			$diary->dnevniki_title		= $title;
			$diary->dnevniki_text		= $description;
			$diary->dnevniki_picture	= !empty ($picture) ? $picture : $diary->dnevniki_picture;
			$diary->update();

			return redirect()->route('ank.diary.id', $user->user_id)
				->with('success','Дневник был обновлен')
				->withInput();

		}

		$diary->user_dnevnik_title	= old('title')	 		? old('title') 			: stripslashes ($diary->dnevniki_title);
		$diary->user_dnevnik_text	= old('description') 	? old('description') 	: $diary->dnevniki_text;

		return response()->view ('ankets.diary_edit',
		[
			'userData'		=> $user,
			'diary'			=> $diary,
		]);
	}
}