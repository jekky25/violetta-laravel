<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

use App\Models\Message;
use App\Models\User;
use App\Models\AnketEvaluation;
use App\Models\Smile;

class PrivmsgController extends Controller
{

	public static $messagePerPage 		= 10;
	public static $messageAnkPerPage 	= 30;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$user 			= Auth::user();
		if (empty ($user)) abort (404);

		$messages 			= Message::getAll($user->user_id, self::$messagePerPage);

		$page 				= $messages->currentPage();
		$pagination 		= Helper::preparePagination ($messages->toArray()['links']);
		return response()->view ('ankets.privmsg',
		[
			'messages' 		=> $messages,
			'pagination'	=> $pagination
		]);
    }

	public function delete(Request $request)
    {
		$user 			= Auth::user();
		if (empty ($user)) abort (404);
		$arParams 		= $request->post();

		if (empty($arParams['mark'])) return redirect()->back()->withInput();
		$markList		= $arParams['mark'];

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('privmsg');
		}

		if ( !empty($arParams['confirm']) ) {
			foreach ($markList as $userId) 
			{
				$messages = Message::getForUser($userId, $user->user_id);

				if (count ($messages) > 0)
				{
					foreach ($messages as $item)
					{
						if ($item->user_poluchil == $user->user_id)
						{
							$item->user_poluchil_del 	= 1;
						}

						if ($item->user_otprav == $user->user_id)
						{
							$item->user_otprav_del 		= 1;
						}
						$item->update();
					}
				}
			}
			return redirect()->route ('privmsg');
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить сообщения этих пользователей?<br /><br />';
		$confirmAction 	= route ('privmsg.delete');
		$hidden			= '';

		foreach ($markList as $item) 
		{
			$hidden .= '<input type="hidden" name="mark[]" value="' . intval($item) . '" />';
		}

		Helper::outMessageInfo($title, $text, $confirmAction, $hidden);
	}

	public function deletePost(Request $request, $id)
	{
		$user 			= Auth::user();
		$message 		= Message::getById($id);

		if (empty ($user) or empty($message)) abort (404);
		$arParams 		= $request->post();
		$user_id 		= $message->user_otprav == $user->user_id ? $message->user_poluchil : $message->user_otprav;

		if ( !empty($arParams['cancel']) ) {
			return redirect()->route ('privmsg.post', $user_id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это сообщение?<br /><br />';
		$confirmAction 	= route ('privmsg.post.delete', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	public function getAnkMess(Request $request, $id)
    {
		$user 			= Auth::user()->load(['visits']);
		$anket 			= User::getById ($id);
		$messages 		= Message::getAllByUser($id, $user->user_id, self::$messageAnkPerPage);
		$vote 			= isset ($request->golos) ? (int)$request->golos : 0;
		$vote 			= $vote > 5 ? 5 : $vote;

		$page 			= $messages->currentPage();
		$pagination 	= Helper::preparePagination ($messages->toArray()['links']);
		$smiles			= Smile::getAll();

		$affectedRows	= AnketEvaluation::getEvaluations($user->user_id, $id);

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

		return response()->view ('ankets.privmsg_id',
		[
			'userData'			=> $user,
			'anketUserData'		=> $anket,
			'ankEvaluationed' 	=> isset($ankEvaluationed) ? $ankEvaluationed : false,
			'messages'			=> $messages,
			'pagination'		=> $pagination,
			'smiles'			=> $smiles
		]);
	}

}

