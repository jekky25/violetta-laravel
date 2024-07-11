<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

use App\Helpers\Helper;
use App\Models\Country;
use App\Models\User;
use App\Models\Diary;

class HomeController extends Controller
{
	public 	$countNewFaces 	= 5;
	public 	$countDiaries 	= 5;

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
     * show the home page
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
	{
		$ages 		= Helper::getAges();
		$countries 	= Country::getAll();
		$newFaces 	= User::newFaces($this->countNewFaces);
		$diaries 	= Diary::get($this->countDiaries);
		return response()->view ('home', 
		[
			'ages'		=> $ages,
			'countries' => $countries,
			'newFaces' 	=> $newFaces,
			'diaries' 	=> $diaries,
		]);
	}
}
