<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Helpers\Helper;

use App\Models\Message;
use App\Models\User;
use App\Models\AnketEvaluation;
use App\Models\Smile;
use App\Mail\Email;

class PrivmsgController extends Controller
{

	public static $messagePerPage 		= 10;
	public static $messageAnkPerPage 	= 30;
	public static $messageSendLimit		= 10;
	public static $siteUrl				= 'www.avioletta.ru';
	public static $siteUrlWithProtocol	= 'http://www.avioletta.ru';


	public static $rulesPost = [
		'message_text'	=> ['required', 'max:3000', 'min:2', 'check_often'],
	];

	public static $errMessagesPost = [
		'message_text.required' 	=> 'Сообщение не заполнено',
		'message_text.max'	 		=> 'Сообщение слишком длинное',
		'message_text.min'	 		=> 'Сообщение слишком короткое',
		'check_often'				=> 'Вы превысили лимит отправляемых сообщений:<br /> не более 10 сообщений за 5 минут'
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

    /**
     * Show the application dashboard.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$user 			= Auth::user();
		if (empty ($user)) abort (404);

		$messages 			= Message::getAll($user->user_id, self::$messagePerPage);
		$messages			= Message::getNewsByUsers($messages, $user);
		$page 				= $messages->currentPage();
		$pagination 		= Helper::preparePagination ($messages->toArray()['links']);
		return response()->view ('ankets.privmsg',
		[
			'messages' 		=> $messages,
			'pagination'	=> $pagination
		]);
    }

	/**
     * Delete user messages
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
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

	/**
     * Delete an user message
     * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

		if ( !empty($arParams['confirm']) ) {
			if ($message->user_otprav == $user->user_id) 
			{
				$message->user_otprav_del = 1;
			} else 
			{
				$message->user_poluchil_del = 1;
			}
			$message->update();
			return redirect()->route ('privmsg.post', $user_id);
		}

		$title 			= 'Информация';
		$text 			= 'Вы уверены, что хотите удалить это сообщение?<br /><br />';
		$confirmAction 	= route ('privmsg.post.delete', $id);
		Helper::outMessageInfo($title, $text, $confirmAction);
	}

	/**
     * Show a page with the user messages
     * @param  \Illuminate\Http\Request  $request
	 * @param  int $id
     * @return \Illuminate\Http\Response
     */
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

		if (count($messages) > 0)
		{
			foreach ($messages as &$item)
			{
				if ($item->mess_new == 1 && $item->user_poluchil == $user->user_id)
				{
					$item->mess_new = 0;
					$item->update();
				}
				$item->privmess_text = Helper::transformSmiles ($item->privmess_text, $smiles);
			}
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

	/**
     * Add an user message
     * @param  \Illuminate\Http\Request  $request
	 * @param  int $id
     * @return void
     */
	public function addPost(Request $request, $id)
    {
		$user 			= Auth::user();
		$anket 			= User::getJustById($id);
		if (empty ($user) or empty ($anket)) abort (404);
		$arParams 		= $request->post();


		Validator::extend('check_often',
            function () use ($user) {
				return count (Message::getByTimeByUser($user->user_id)) > self::$messageSendLimit ? false : true;
            });

		$validator = Validator::make($arParams, self::$rulesPost, self::$errMessagesPost);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$strError = $messages;

			return redirect()->back()
						->withErrors($strError, 'comment')
						->withInput();
		}

		$message = $arParams['message_text'];
		$aFields = [
			'user_otprav'				=> $user->user_id,
			'user_poluchil'				=> $id,
			'user_otprav_del'			=> 0,
			'user_poluchil_del'			=> 0,
			'time'						=> time(),
			'mess_new'					=> 1,
			'privmess_text'				=> str_replace("\'", "''", $message)
		];

		$oMessage = new Message($aFields);
		$oMessage->save();

		if ($user->dont_send_email != 1) {
			$oMail = new \stdClass();
			$oMail->emailTo 		= $anket->user_mail;
			$oMail->emailFrom 		= config('mail.email_main');
			$oMail->template 		= 'mails.notify';
			$oMail->templateText 	= 'mails.txt.notify';
			$oMail->name 			= $anket->user_name;
			$oMail->sitename 		= '<a href="' . self::$siteUrlWithProtocol . '">' . self::$siteUrl . '</a>';
			$oMail->sitenameNoTags	= self::$siteUrl;
			$oMail->subject			= "Вам пришло новое сообщение на www.avioletta.ru";

			Mail::mailer(config('mail.mail_mode'))
        	->to($oMail->emailTo)
        	->send(new Email($oMail));
	
		}

		return redirect()->back()
		->with('success','Сообщение успешно отправлено')
		->withInput();
	}
}