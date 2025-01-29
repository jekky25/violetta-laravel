<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\GoroskopInterface;
use App\Interfaces\GoroskopTypeInterface;
use App\includes\Horoscope\ZodiacHoroscope;
use App\includes\Horoscope\EasternHoroscope;
use App\includes\Horoscope\GallHoroscope;
use App\includes\Horoscope\FloversHoroscope;
use App\includes\Horoscope\TalismanHoroscope;

class GoroskopController extends Controller
{
	public 	$typeGor 	= 1;
	const 	HOROSCOPES 	= [
			1 => ZodiacHoroscope::class,
			2 => EasternHoroscope::class,
			3 => GallHoroscope::class,
			4 => FloversHoroscope::class,
			5 => TalismanHoroscope::class
		];

	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected GoroskopInterface $goroskopRepository,
		protected GoroskopTypeInterface $goroskopTypeRepository
	)
	{
	}

	/**
	* Show the page with goroskops
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$goroskops		= $this->goroskopRepository->getByType($this->typeGor);
		$goroskopsType	= $this->goroskopTypeRepository->getNotByType($this->typeGor);
		if (empty ($goroskops)) abort(404);
		$horoscope = new(self::HOROSCOPES[1]);

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text' 		=> $horoscope->getText(),
			'goroskopsTitle' 	=> $horoscope->getTitle(),
			'goroskops_type' 	=> $goroskopsType
		]);
	}

	/**
	* show a goroskop page by id
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getItem($id)
	{
		$goroskop		= $this->goroskopRepository->getById($id);
		$this->typeGor	= $goroskop->type;

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $this->goroskopRepository->getByType($this->typeGor),
			'goroskop'			=> $goroskop,
			'zodiak_text' 		=> $goroskop->description,
			'goroskopsTitle' 	=> $goroskop->name,
			'goroskops_type' 	=> $this->goroskopTypeRepository->getNotByType($this->typeGor)
		]);
	}

	/**
	* show the page with gorokop types
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getType($id = 0)
	{
		$type = ($id > 0 && $id <= 5) ? (int) $id : 1;
		$horoscope = new(self::HOROSCOPES[$type]);

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $this->goroskopRepository->getByType($type),
			'zodiak_text'		=> $horoscope->getText(),
			'goroskopsTitle'	=> $horoscope->getTitle(),
			'goroskops_type'	=> $this->goroskopTypeRepository->getNotByType($type)
		]);

	}
}