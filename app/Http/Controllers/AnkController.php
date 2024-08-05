<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use App\Models\User;
use App\Models\AnketVisit;
use App\Models\AnketEvaluation;
use App\Models\Vars;
use App\Models\CommentPhoto;
use App\Models\Photo;
use App\Models\Diary;
use App\Models\DiaryComment;
use App\Interfaces\AnketEvaluationInterface;
use App\Helpers\Helper;

class AnkController extends Controller
{
	public static $getProps = [
		'\\App\\Models\\Body' 			=> ['prop' =>'user_body', 				'ank_prop' =>'body'],
		'\\App\\Models\\HairColor' 		=> ['prop' =>'user_hair_color', 		'ank_prop' =>'hair_color'],
		'\\App\\Models\\HairType' 		=> ['prop' =>'user_hair_type', 			'ank_prop' =>'hair_type'],
		'\\App\\Models\\Eyes' 			=> ['prop' =>'user_eyes',	 			'ank_prop' =>'eyes'],
		'\\App\\Models\\FamilyStatus' 	=> ['prop' =>'user_sem_polozh',			'ank_prop' =>'family_status'],
		'\\App\\Models\\Children' 		=> ['prop' =>'user_children',			'ank_prop' =>'children'],
		'\\App\\Models\\Education' 		=> ['prop' =>'user_education',			'ank_prop' =>'education'],
		'\\App\\Models\\Smoke' 			=> ['prop' =>'user_smoke',				'ank_prop' =>'smoke'],
		'\\App\\Models\\Spirt' 			=> ['prop' =>'user_spirt',				'ank_prop' =>'spirt'],
		'\\App\\Models\\HelpMoney' 		=> ['prop' =>'user_help_money',			'ank_prop' =>'help_money'],
		'\\App\\Models\\SexOrient' 		=> ['prop' =>'user_sex_oriebt',			'ank_prop' =>'sex_orient'],
		'\\App\\Models\\Country'		=> ['prop' =>'user_partner_country',	'ank_prop' =>'partner_country'],
		'\\App\\Models\\Region'			=> ['prop' =>'user_partner_region',		'ank_prop' =>'partner_region'],
		'\\App\\Models\\City'			=> ['prop' =>'user_partner_city',		'ank_prop' =>'partner_city']
	  ];

	public static $rulesDiary = [
		'description'	=> ['required', 'max:3000', 'min:2'],
		'title'			=> ['required', 'max:255', 'min:2'],
		'photo_link'	=> ['file', 'image', 'max:4048'],
	];

	public static $rulesCommentDiary = [
		'description'	=> ['required', 'max:3000', 'min:2'],
		'title'			=> ['max:255'],
		'photo_link'	=> ['file', 'image', 'max:4048'],
	];

	public static $errMessagesDiary = [
		'description.required' 	=> 'Описание не заполнено',
		'description.max'	 	=> 'Описание слишком длинное',
		'description.min'	 	=> 'Описание слишком короткое',
		'title.required'	 	=> 'Заголовок не заполнен',
		'title.max'	 			=> 'Заголовок слишком длинный',
		'title.min'	 			=> 'Заголовок слишком короткий',
		'photo_link.image'		=> 'Файл не является изображением',
		'photo_link.max'		=> 'Файл слишком большой',
	];


	public static $visitDays 			= 30;
	public $commentCountPerPage 		= 100;
	public static $diaryPerPage 		= 10;
	public static $diaryCommentsPerPage	= 20;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected AnketEvaluationInterface $anketEvaluationRepository,
	)
	{
	}

	/**
	* Show a profile page
	* @param  \Illuminate\Http\Request  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getAnk (Request $request, $id)
	{
		$user 	= Auth::user();
		$mode 	= Route::currentRouteName() == 'ank.full.id' ? 'full' : '';
		$anket 	= User::getById ($id, $mode);

		$vote 	= isset ($request->golos) ? (int)$request->golos : 0;
		$vote 	= $vote > 5 ? 5 : $vote;

		if (empty ($anket)) abort(404);

		/*
		$sql = 'SELECT user_reg_is
		FROM session_on_line
		WHERE user_reg_is = ' . $row['user_id'] . '
		LIMIT 0,1';
	$result3 = $db->query($sql);
	$row3 = $db->sql_fetchrow($result3);
	$row ['user_reg_is'] = !empty($row3 ['user_reg_is']) ? $row3 ['user_reg_is'] : 0;

	$row ['online_per'] = ($row['user_id'] == $row['user_reg_is']) ? '<img title="на сайте" class="online1" alt="на сайте" src="templates/image/on_line.gif">' : '';
	if ($row ['online_per'] != '') {
		$row ['user_last_visit'] = $row ['online_per'] . ' на сайте';
	} else {
		$row ['user_last_visit'] = ($row['user_sex'] == MEN) ? 'Был на сайте: ' : 'Была на сайте: ';
		$row ['user_last_visit'] .= last_visit($row['user_lastvisit']);
	}*/

		$visits = AnketVisit::getVisitsByUserId ($id, self::$visitDays);
		
		$anket->userank_visits_month = !empty ($visits) ? count ($visits) : 0 ;

		//making meet targets
		$anket->getPropertyFew('App\Models\MeetTarget',	$anket->user_target_meet, 'target_meet');

		//making interests
		$anket->getPropertyFew('App\Models\Interest',	$anket->user_interests, 'interests');
		//making an ankets review and a count of views
		if (!empty ($user))
		{
			$ankVisits = AnketVisit::getVisitsByUserId ($id, self::$visitDays, $user->user_id);
			$anket->ankVisits = count($ankVisits);
			if ($anket->ankVisits == 0 && $user->user_id != $id && $user->user_id > 1)
			{
				AnketVisit::insertVisit($id);
				AnketVisit::removeOld(self::$visitDays);
			} elseif ($anket->ankVisits > 0 && $user->user_id != $id)
				AnketVisit::updateVisit($id);

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
			
						$oAnketEvaluation = new AnketEvaluation ($aFields);
						$oAnketEvaluation->save();

						$ankEvaluationed = true;
					}

					$voteSum = $this->anketEvaluationRepository->getSum ($id);
					if ($voteSum > 0)
					{
						$anket = User::getJustById($id);
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
			$anket->getPropertyFew('App\Models\MeetTarget',	$anket->user_speak_lang, 'speak_lang_str');

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
			$anket->getPropertyFew('App\Models\Body',	$anket->user_partner_body, 'partner_body');
			//making partner languages
			$anket->getPropertyFew('App\Models\SpeakLang',	$anket->user_partner_speak_lang, 'partner_speak_lang');
			//making partner education
			$anket->getPropertyFew('App\Models\Education',	$anket->user_partner_education, 'partner_education');
			//making partner smoke
			$anket->getPropertyFew('App\Models\Smoke',	$anket->user_partner_smoke, 'partner_smoke');
			//making partner spirt
			$anket->getPropertyFew('App\Models\Spirt',	$anket->user_partner_spirt, 'partner_spirt');

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
			$photo = Photo::getById ($id);
			if (empty ($photo)) abort (404);
			$id = $photo->user_id;
		}
		$anket = User::getById ($id);
		if (!count ($anket->photo)) abort (404);
		$user = Auth::user();
		$user = !empty($user) ? $user->load(['visits']) : null;
		$visits = AnketVisit::getVisitsByUserId ($id, self::$visitDays);
		$vars 	= Vars::getAll();

		$ankVisits = AnketVisit::getVisitsByUserId ($id, self::$visitDays, $user->user_id);
		$anket->ankVisits = count($ankVisits);

		if ($anket->ankVisits == 0 && $user->user_id != $id && $user->user_id > 1) 
		{
			AnketVisit::insertVisit($id);
			AnketVisit::removeOld(self::$visitDays);
		} elseif ($anket->ankVisits > 0 && $user->user_id != $id)
			AnketVisit::updateVisit($id);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function postComment (Request $request, $id)
	{
		//making sending comment
		$user 			= Auth::user();
		$arParams 		= $request->post();
		$description 	= $request->has('description') ? $request->description : '';
		
		$rules = [
			'description'	=> ['required', 'max:1000', 'min:2']
		];
		$errMessages = [
				'description.required' 	=> 'Комментарий не заполнен',
				'description.max'	 	=> 'Ваш комментарий слишком длинный',
				'description.min'	 	=> 'Ваш комментарий слишком короткий',
		];

		$validator = Validator::make($arParams, $rules, $errMessages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

		$aFields = [
			'foto_id'					=> $id,
			'user_id'					=> $user->user_id,
			'comments_description' 		=> str_replace("\'", "''", $description),
			'time'						=> time()
		];

		$oComment = new CommentPhoto ($aFields);
		$oComment->save();

		return redirect()->back()
		->with('success','Сообщение успешно отправлено')
		->withInput();
	}

	
	/**
	 * show an user diary page
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function getDiary (Request $request, $id)
	{
		$anket 	= User::getById ($id);
		if (empty ($anket->photo)) abort (404);

		$diaries = Diary::getByUser (self::$diaryPerPage, $id);
		if (count ($diaries) == 0) abort (404);

		$page 				= $diaries->currentPage();
		$pagination 		= Helper::preparePagination ($diaries->toArray()['links']);

		return response()->view ('ankets.diary',
		[
			'userData'		=> $anket,
			'diaries'		=> $diaries,
			'page'			=> $page,
			'pagination'	=> $pagination
		]);
	}

	/**
	 * add an user diary
     * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function addDiary (Request $request)
	{
		$user 			= Auth::user();
		if (empty ($user)) abort (404);
		$arParams 		= $request->post();
		$description 	= $request->has('description') ? $request->description : '';
		$files 			= $request->file();

		$arParams		= array_merge($arParams, $files);
		$validator = Validator::make($arParams, self::$rulesDiary, self::$errMessagesDiary);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

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

		$oComment = new Diary ($aFields);
		$oComment->save();

		return redirect()->back()
		->with('success','Дневник успешно добавлен')
		->withInput();
	}

	/**
	 * delete the user diary
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return void
	 */
	public function delDiary (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= Diary::getByUserAndId($id, $user->user_id);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return void
	 */
	public function delDiaryPhoto (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= Diary::getByUserAndId($id, $user->user_id);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function editDiary (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$diary 			= Diary::getByUserAndId($id, $user->user_id);
		if (empty ($diary)) abort (404);

		$arParams					= $request->post();
		$files 						= $request->file();

		if (!empty($arParams['otsil']))
		{
			$arParams			= array_merge($arParams, $files);
			$validator 			= Validator::make($arParams, self::$rulesDiary, self::$errMessagesDiary);

			if ($validator->fails()) {
				$messages 		= $validator->messages();
				$strError 		= $messages;

				return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
			}

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

	/**
	 * show a comments diary page
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */	
	public function getDiaryComments (Request $request, $id)
	{
		$comments 	= DiaryComment::getByDiary (self::$diaryCommentsPerPage, $id);
		$diary 		= Diary::getById ($id);

		if (empty ($diary) || empty ($diary->user)) abort (404);
		$page 				= $comments->currentPage();
		$pagination 		= Helper::preparePagination ($comments->toArray()['links']);
		return response()->view ('ankets.comments',
		[
			'userData'		=> $diary->user,
			'diary'			=> $diary,
			'comments'		=> $comments,
			'pagination'	=> $pagination
		]);
	}

	/**
	 * add a comment of the diary
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */	
	public function addDiaryComment (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user)) abort (404);
		$arParams 		= $request->post();
		$description 	= $request->has('description') ? $request->description : '';
		$files 			= $request->file();

		$arParams		= array_merge($arParams, $files);
		$validator = Validator::make($arParams, self::$rulesCommentDiary, self::$errMessagesDiary);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

		$title			= strip_tags($arParams['title'],"<b><strong><i>");
		$description	= strip_tags($arParams['description'],"<b><strong><i>");

		if (!empty($arParams['photo_link']))
		{
			$picture = Helper::fotoUpload($arParams['photo_link'], 0, 'img/dnev_comment/');
		}

		$aFields = [
			'comment_dnevnik_id'		=> $id,
			'comment_dnevnik_user_id'	=> $user->user_id,
			'comment_title'				=> $title,
			'comment_text'				=> $description,
			'comment_picture'			=> !empty ($picture) ? $picture : "0",
			'comment_time'				=> time()
		];

		$oComment = new DiaryComment ($aFields);
		$oComment->save();

		return redirect()->back()
		->with('success','Комментарий успешно добавлен')
		->withInput();
	}

	/**
	 * delete a comment of the diary
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return void
	 */	
	public function delDiaryComment (Request $request, $id)
	{
		$user			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$comment 			= DiaryComment::getByUserAndId($id, $user->user_id);

		if (empty ($comment)) abort (404);

		$arParams 		= $request->post();

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('ank.diary.comments', $comment->comment_dnevnik_id);
		}

		if ( !empty($arParams['confirm']) ) {
			if (!empty($comment->picture_url) && file_exists($comment->picture_url))
			{
				unlink($comment->picture_url);
			}

			$comment->delete();
			return redirect()->route ('ank.diary.comments', $comment->comment_dnevnik_id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить эту запись<br /><br />';
		$confirmAction 	= route ('ank.diary.comment.delete.id', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
	 * delete thre picture of the diary comment
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return void
	 */	
	public function delDiaryCommentPhoto (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$comment 			= DiaryComment::getByUserAndId($id, $user->user_id);
		if (empty ($comment)) abort (404);

		$arParams 		= $request->post();

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('ank.diary.comment.edit.id', $id);
		}

		if ( !empty($arParams['confirm']) ) {
			if (!empty($comment->picture_url) && file_exists($comment->picture_url))
			{
				unlink($comment->picture_url);
			}
			$comment->comment_picture = 0;
			$comment->update();
			return redirect()->route ('ank.diary.comment.edit.id', $id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это фото<br /><br />';
		$confirmAction 	= route ('ank.diary.comment.delete.photo.id', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
	 * show an edit comment page and update the comment
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
	 * @return \Illuminate\Http\Response
	 */	
	public function editDiaryComment (Request $request, $id)
	{
		$user 			= Auth::user();
		if (empty ($user) ||  $id == 0) abort (404);
		$comment 			= DiaryComment::getByUserAndId($id, $user->user_id);
		if (empty ($comment)) abort (404);

		$arParams					= $request->post();
		$files 						= $request->file();

		if (!empty($arParams['otsil']))
		{
			$arParams			= array_merge($arParams, $files);
			$validator 			= Validator::make($arParams, self::$rulesDiary, self::$errMessagesDiary);

			if ($validator->fails()) {
				$messages 		= $validator->messages();
				$strError 		= $messages;

				return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
			}

			$title				= strip_tags($arParams['title'],"<b><strong><i>");
			$description		= strip_tags($arParams['description'],"<b><strong><i>");
	
			if (!empty($arParams['photo_link']))
			{
				$picture = Helper::fotoUpload($arParams['photo_link'], 0, 'img/dnev_comment/');
			}
	
			$comment->comment_title		= $title;
			$comment->comment_text		= $description;
			$comment->comment_picture	= !empty ($picture) ? $picture : $comment->comment_picture;

			$comment->update();

			return redirect()->route('ank.diary.comments', $comment->comment_dnevnik_id)
				->with('success','Комментарий был обновлен')
				->withInput();
		}

		$comment->title	= old('title')	 		? old('title') 			: stripslashes ($comment->comment_title);
		$comment->text	= old('description') 	? old('description') 	: $comment->comment_text;

		return response()->view ('ankets.diary_comment_edit',
		[
			'userData'		=> $user,
			'comment'		=> $comment,
		]);

	}
}