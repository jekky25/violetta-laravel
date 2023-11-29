<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Diary;
use App\Helpers\Helper;

class DiaryController extends Controller
{
	public $countPerPage 	= 10;

	/**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
    }

	public function index(Request $request)
    {
		$diaries		= Diary::getAll($this->countPerPage);
		$page			= $diaries->currentPage();
		$pagination		= Helper::preparePagination ($diaries->toArray()['links']);

		return response()->view ('diaries', 
		[
			'diaries'				=> $diaries,
			'pagination'			=> $pagination,
		]);
    }

}