<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CountryInterface;
use App\Interfaces\DiaryInterface;
use App\Interfaces\UserInterface;
use App\Services\DataService;
use App\Fields\SearchField;
use App\Http\Resources\Profile\ProfileShortResource;
use App\Http\Resources\Diary\DiaryResource;

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
	) {}

	/**
	 * show the home page
	 * @return \Illuminate\Http\Response
	 */
	public function index(DataService $data, SearchField $fields)
	{
		return response()->view(
			'home',
			[
				'ages'		=> $data->getAges(),
				'fields'	=> $fields->get()
			]
		);
	}

	/**
	 * Get new faces
	 * @return \Illuminate\Http\Response
	 */
	public function newFaces()
	{
		$profiles	= $this->userRepository->newFaces($this->countNewFaces);
		return ProfileShortResource::collection($profiles);
	}

	/**
	 * Get diaries
	 * @return \Illuminate\Http\Response
	 */
	public function diaries()
	{
		$diaries	= $this->diaryRepository->get($this->countDiaries);
		return DiaryResource::collection($diaries);
	}
}
