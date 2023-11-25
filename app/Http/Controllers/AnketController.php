<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\AnketVisit;
use App\Models\User;
use App\Helpers\Helper;

class AnketController extends Controller
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

	public function getPopularAnkets ($sex = 'women')
	{
		$s 					= $sex == 'men' ? MEN 		: WOMEN;
		$popSex 			= $sex == 'men' ? 'мужчины' : 'женщины';
		$ankets 			= User::getPopul($this->countPerPage, $s);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		return response()->view ('ankets.popular_search', 
		[
			'popSex'		=> $popSex,
			'sex'			=> $sex,
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}


	public function getBirthdayAnkets ()
	{
		$ankets 			= User::getBirthday($this->countPerPage);

		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		return response()->view ('ankets.birthday_search', 
		[
			'page'			=> $page,
			'ankets'		=> $ankets,
			'pagination'	=> $pagination
		]);
	}

	public function getAnkets ($sex = '', $op = '')
	{
		$s					= $sex == 'men' ? MEN 		: WOMEN;
		$popSex 			= $sex == 'men' ? 'мужчины' : 'женщины';
		$ankets 			= User::getOp($this->countPerPage, $s);
		$page 				= $ankets->currentPage();
		$pagination 		= Helper::preparePagination ($ankets->toArray()['links']);

		$startShowAnk 		= (($ankets->currentPage() - 1) * $this->countPerPage) + 1;
		$endShowAnk			= $ankets->currentPage() * $this->countPerPage;

		$countSearchAnkStr = 'Найдено анкет: (' . $startShowAnk . '-' . $endShowAnk . ') из ' . $ankets->total();

		return response()->view ('ankets.id', 
		[
			'popSex'			=> $popSex,
			'sex'				=> $sex,
			'page'				=> $page,
			'ankets'			=> $ankets,
			'countSearchAnkStr' => $countSearchAnkStr,
			'pagination'		=> $pagination
		]);
	}
	

}