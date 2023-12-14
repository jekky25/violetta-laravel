<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AnketVisit;
use App\Models\MeetTarget;
use App\Models\Interest;
use App\Models\AnketEvaluation;
use App\Models\Body;
use App\Models\Vars;
use App\Models\CommentPhoto;
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


	public static $visitDays 		= 30;
	public $commentCountPerPage 	= 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
    }

	public function getAnk (Request $request, $id)
	{
		$user 	= Auth::user()->load(['visits']);
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

			$affectedRows = AnketEvaluation::getEvauletions($user->user_id, $id);

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

					$voteSum = AnketEvaluation::getSum ($id);
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
			$anket->getPropertyFew('App\Models\MeetTarget',	$anket->user_speak_lang, 'speak_lang');

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

	public function getPhoto (Request $request, $id)
	{
		$anket 	= User::getById ($id);
		if (empty ($anket->photo)) abort (404);
		$user 	= Auth::user()->load(['visits']);
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

		$affectedRows 		= AnketEvaluation::getEvauletions($user->user_id, $id);

		foreach ($anket->photo as &$item)
		{
			$item->comment	= $item->comment->slice(0, $this->commentCountPerPage);
		}

		$anket->mainPhoto 	= $anket->photo[0];
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
				}
				$_item->add_time = date("d.m.y H:i",$_item->time);
				$_item->comments_description = str_replace("\n", "\n<br />\n", $_item->comments_description);
			}
		}

		/*
				//risuem otpravit comment
				$description = $conf -> post ('description');
				if ($send) {
					if ($description != '') {
						$description =  str_replace("\'", "''", $description);
						$sql = 'INSERT ' . COMMENTS_FOTOS_TABLE . '
								SET foto_id = ' . $foto_id . ', user_id = ' . $userdata['user_id'] . ', comments_description = "' . $description . '",
								time = ' . time();
		
						if(!($db->query($sql)) )
							message_die_sql($sql, 'ank\foto.php; 195');
						redirect('index.php?mod=ank&foto=' . $foto_id);
					} else {
						$error = 'не введено сообщение';
					}
				}
		*/

		return response()->view ('ankets.photo',
		[
			'userData'			=> $anket,
		]);
	}
}