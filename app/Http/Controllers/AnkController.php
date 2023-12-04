<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AnketVisit;
use App\Models\MeetTarget;
use App\Models\Interest;
use App\Models\AnketEvaluation;
use App\Helpers\Helper;

class AnkController extends Controller
{
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

		$anket = User::getById ($id);

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

		$meetTarget = unserialize($anket->user_target_meet);

		//making meet targets
		if ($anket->user_target_meet != "N;" && !empty($meetTarget[0]))
		{
			$oMeetTarget = MeetTarget::getAll();

			$i = 0;
			$anket->target_meet_out = '';
			$arTargetMeet = [];
			foreach ($meetTarget as $k=>$v)
			{
				$i++;
				foreach ($oMeetTarget as $mT)
				{
					if ($v == $mT->id)
					{
						$arTargetMeet[] = $mT->name;
						break;
					}
				}
			}

			$anket->target_meet_out = implode (', ', $arTargetMeet);
		}

		//making interests
		$interests = unserialize($anket->user_interests);

		if ($anket->user_interests != "N;" && !empty ($interests[0]))
		{
			$oInterest = Interest::getAll();

			$i = 0;
			$anket->interest_out = '';
			$arInterest = [];
			foreach ($interests as $k=>$v)
			{
				$i++;
				foreach ($oInterest as $mT)
				{
					if ($v == $mT->id)
					{
						$arInterest[] = $mT->name;
						break;
					}
				}
			}

			$anket->interests_out = implode (', ', $arInterest);
		}

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


		return response()->view ('ankets.short',
		[
			'userData'	=> $anket,
			'ankEvaluationed' => isset($ankEvaluationed) ? $ankEvaluationed : false
		]);
	}
}