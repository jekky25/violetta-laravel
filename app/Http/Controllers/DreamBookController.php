<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\Tstr;
use App\Helpers\Helper;
use App\Models\DreamBook;

class DreamBookController extends Controller
{
	use Tstr;

	public $countPerPage 	= 30;
	public $op				= 1;
	private $pattern		= '/sonnik_id([0-9]+).html/i';
	private $replacement 	= 'dreambook/$1.html';
	

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
     * Show the page with dreambooks
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
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

	/**
     * Show the page with a dreambook
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
	public function getItem(Request $request, $id)
	{
		$dreamBookLiterals		= DreamBook::getLiter();		
		$dreambook 				= DreamBook::getById($id);

		$dreambook->description = $this->replaceStringByPattern ($dreambook->description, $this->pattern, $this->replacement);
		if (empty ($dreambook)) abort(404);

		return response()->view ('dreambooks_id', 
		[
			'dreamBookLiterals'		=> $dreamBookLiterals,
			'dreambook'			=> $dreambook
		]);

	}
}

