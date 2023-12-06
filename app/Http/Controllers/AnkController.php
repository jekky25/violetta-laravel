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
		$user = Auth::user()->load(['visits']);

		$mode = Route::currentRouteName() == 'ank.full.id' ? 'full' : '';

		$anket = User::getById ($id, $mode);

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

		$visits = AnketVisit::getVisitsByUserId ($id, 30);
		
		$anket->userank_visits_month = !empty ($visits) ? count ($visits) : 0 ;

		//making meet targets
		$anket->getPropertyFew('App\Models\MeetTarget',	$anket->user_target_meet, 'target_meet');

		//making interests
		$anket->getPropertyFew('App\Models\Interest',	$anket->user_interests, 'interests');

		//making an ankets review and a count of views
		if (!empty ($user))
		{
			$ankVisits = AnketVisit::getVisitsByUserId ($id, 30, $user->user_id);
			$anket->ankVisits = count($ankVisits);
			if ($anket->ankVisits == 0 && $user->user_id != $id && $user->user_id > 1) 
			{
				$aFields = [
					'user_id_prosm'		=> $anket->user_id,
					'ank_user_id'		=> $user->user_id,
					'ank_time'			=> time()
				];
		
				$oAnketVisit = new AnketVisit ($aFields);
				$oAnketVisit->save();
				$time = \Carbon\Carbon::now()->subDays(30)->toArray();

				$affectedRows = AnketVisit::where('ank_time', '<', ($time['timestamp']))->delete();

			} elseif ($anket->ankVisits > 0 && $user->user_id != $id) 
			{
				$aFields = [
					'user_id_prosm' => $id,
					'ank_user_id' 	=> $user->user_id
				];
				$ankVisits = AnketVisit::getByFields ($aFields);
				if (!empty($ankVisits))
				{
					$ankVisits->ank_time = time();
					$ankVisits->save();
				}
			}

			$affectedRows = AnketEvaluation::getEvauletions($user->user_id, $id);

			if (count ($affectedRows) == 0) 
			{
				/*
				if ($send_golos && $golos) {
				  $golos = intval ($golos);
				  if ($golos > 5)
					$golos = 5;
				  if ($golos == 0)
					$golos = 1;
		  
				if ($userdata['user_id'] != $id) {
					$sql = 'INSERT ' . OCENKA_ANKET_TABLE . '
						SET user_id = ' . $userdata['user_id'] . ', user_id_ocenka = ' . $id . ', ball = ' . $golos . ', time= ' . time();
					if(!($db->query($sql)) )
						message_die_sql($sql, 'ank\index.php; 393');
					$AnkOcenena = true;
				}
				$sql = 'SELECT SUM(ball) as sum_ank FROM ' . OCENKA_ANKET_TABLE . '
						WHERE user_id_ocenka = ' . $id;
				if(!($result_ocenka = $db->query($sql)) )
					message_die_sql($sql, 'ank\index.php; 398');

				$row_ocenka = mysqli_fetch_array($result_ocenka);

				$sql = 'UPDATE ' . USERS_TABLE . ' SET
					user_reiting = "' . $row_ocenka['sum_ank'] . '"
					WHERE user_id = ' . $id;
				if(!($db->query($sql)) )
					message_die_sql($sql, 'ank\index.php; 406');
					redirect('index.php?mod=ank' . $op_out . '&id='. $id . '&succgolos=1', true);	
				}
				*/
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
}