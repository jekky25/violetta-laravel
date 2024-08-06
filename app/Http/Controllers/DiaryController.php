<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\DiaryInterface;
use App\Helpers\Helper;

class DiaryController extends Controller
{
	public $countPerPage 	= 10;

	/**
	* Create a new controller instance.
	*
	* @return void
	*/

	public function __construct(
		protected DiaryInterface $diaryRepository,
	)
	{
	}

	/**
	* show the page with diaries
	*/
	public function index()
	{
		$diaries		= $this->diaryRepository->getAll($this->countPerPage);
		$page			= $diaries->currentPage();
		$pagination		= Helper::preparePagination ($diaries->toArray()['links']);

		return response()->view ('diaries', 
		[
			'diaries'				=> $diaries,
			'pagination'			=> $pagination,
		]);
    }

}