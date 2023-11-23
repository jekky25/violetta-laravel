<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Screen;
use App\Helpers\Helper;

class ScreenController extends Controller
{

	public $countPerPage 	= 6;

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
		$screen = Screen::get($this->countPerPage);
		$page 				= $screen->currentPage();
		$pagination 		= Helper::preparePagination ($screen->toArray()['links']);

		return response()->view ('screensavers', 
		[
			'screen'			=> $screen,
			'pagination'		=> $pagination,
			'numScreens'		=> $screen->total()
		]);
    }
}

