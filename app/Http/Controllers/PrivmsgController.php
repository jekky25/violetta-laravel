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

		$messages 		= Message::getAll($user->user_id, self::$messagePerPage)/*->toArray()*/;

		$page 				= $messages->currentPage();
		$pagination 		= Helper::preparePagination ($messages->toArray()['links']);

		return response()->view ('ankets.privmsg',
		[
			'messages' 		=> $messages,
			'pagination'	=> $pagination
		]);
    }
}

