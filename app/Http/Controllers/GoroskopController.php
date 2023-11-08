<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Goroskop;
use App\Models\GoroskopType;

class GoroskopController extends Controller
{

	public 	$typeGor 	= 1;

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
		$goroskops 		= Goroskop::getByType($this->typeGor);
		$goroskopsType 	= GoroskopType::getNotByType($this->typeGor);

		if (empty ($goroskops)) abort(404);

		include('../app/includes/goroskop/zodiak_text.php');

		$goroskopsTitle  = 'Гороскопы - Зодиак';

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text' 		=> $zodiakText,
			'goroskopsTitle' 	=> $goroskopsTitle,
			'goroskops_type' 	=> $goroskopsType,
			
		]);
    }
}
