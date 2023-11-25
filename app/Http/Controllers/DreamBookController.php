<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\DreamBook;

class DreamBookController extends Controller
{
	public $countPerPage 	= 30;
	public $op				= 1;
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
    public function index(Request $request, $id = 1)
    {
		$dreamBookLiterals		= DreamBook::getLiter();
		$op						= !empty ($id) ? $id : $this->op;
		$words					= DreamBook::get($this->countPerPage, $op);
		$page					= $words->currentPage();
		$pagination				= Helper::preparePagination ($words->toArray()['links']);

		return response()->view ('dreambooks', 
		[
			'dreamBookLiterals'		=> $dreamBookLiterals,
			'words'					=> $words,
			'page'					=> $page,
			'pagination'			=> $pagination,
		]);
    }
}

