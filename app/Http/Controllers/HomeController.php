<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CountryInterface;
use App\Interfaces\DiaryInterface;
use App\Helpers\Helper;
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
	public function __construct(
		protected CountryInterface $countryRepository,
		protected DiaryInterface $diaryRepository,
	)
	{
	}

	/**
	* show the home page
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$ages		= Helper::getAges();
		$countries	= $this->countryRepository->getAll();
		$newFaces	= User::newFaces($this->countNewFaces);
		$diaries	= $this->diaryRepository->get($this->countDiaries);
		return response()->view ('home', 
		[
			'ages'		=> $ages,
			'countries' => $countries,
			'newFaces' 	=> $newFaces,
			'diaries' 	=> $diaries,
		]);
	}
}