<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CountryInterface;
use App\Interfaces\DiaryInterface;
use App\Interfaces\UserInterface;
use App\Helpers\Helper;

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
		protected UserInterface $userRepository
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
		$newFaces	= $this->userRepository->newFaces($this->countNewFaces);
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