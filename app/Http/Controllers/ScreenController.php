<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Screen;
use App\Models\CommentScreen;
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
		$screens 			= Screen::get($this->countPerPage);
		$page 				= $screens->currentPage();
		$pagination 		= Helper::preparePagination ($screens->toArray()['links']);
		return response()->view ('screensavers', 
		[
			'page'				=> $page,
			'screens'			=> $screens,
			'pagination'		=> $pagination,
			'numScreens'		=> $screens->total()
		]);
    }

	public function getItem(Request $request, $id)
	{
		$screen 			= Screen::getById($id);
		if (empty ($screen)) abort(404);

		$screen->size_scr 	= Helper::formatFileSize($screen->size_scr);
		$screen->size_rar 	= Helper::formatFileSize($screen->size_rar);

		$comments 			= CommentScreen::getByScrId($id);


		return response()->view ('screensavers_id', 
		[
			'screen'			=> $screen,
			'comments'			=> $comments
		]);

	}
}

