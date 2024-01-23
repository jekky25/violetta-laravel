<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

use App\Models\Message;

class PrivmsgController extends Controller
{

	public static $messagePerPage 	= 10;

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

}

