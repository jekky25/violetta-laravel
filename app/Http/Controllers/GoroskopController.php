<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\GoroskopInterface;
use App\Interfaces\GoroskopTypeInterface;

class GoroskopController extends Controller
{
	public 	$typeGor 	= 1;

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
		include(base_path() . '/app/includes/goroskop/zodiak_text.php');

		$goroskopsTitle  = 'Гороскопы - Зодиак';

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text' 		=> $zodiakText,
			'goroskopsTitle' 	=> $goroskopsTitle,
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
		$this->typeGor	= $goroskop->gor_type;
		$goroskops		= $this->goroskopRepository->getByType($this->typeGor);
		$goroskopsType	= $this->goroskopTypeRepository->getNotByType($this->typeGor);

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'goroskop'			=> $goroskop,
			'zodiak_text' 		=> $goroskop->gor_text,
			'goroskopsTitle' 	=> $goroskop->gor_name,
			'goroskops_type' 	=> $goroskopsType
		]);
	}

	/**
	* show the page with gorokop types
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getType($id = 0)
	{
		$id = (int) $id;
		if ($id == 0 && $id > 5) abort(404);
		$goroskopsType = $id;
		switch ($id) {
			case 2:
				include(base_path() . '/app/includes/goroskop/vost_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Восточный гороскоп';
				$title_id = 'Восточный гороскоп';
				break;
			case 2:
				include(base_path() . '/app/includes/goroskop/gall_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Галлийский гороскоп';
				$title_id = 'Галлийский гороскоп';
				break;
			case 4:
				include(base_path() . '/app/includes/goroskop/cvet_goroskop_text.php');
				$goroskopsTitle = 'Гороскопы - Гороскоп цветов';
				$title_id = 'Гороскоп цветов';
				break;
			case 5:
				include(base_path() . '/app/includes/goroskop/talisman_text.php');
				$goroskopsTitle = 'Гороскопы - Талисманы';
				$title_id = 'Талисманы';
				break;
			default:		
				include(base_path() . '/app/includes/goroskop/zodiak_text.php');
				$goroskopsTitle = 'Гороскопы - Зодиак';
				$title_id = 'Зодиак';
		}

		$goroskops		= $this->goroskopRepository->getByType($goroskopsType);
		$goroskopsType	= $this->goroskopTypeRepository->getNotByType($goroskopsType);

		return response()->view ('goroskop', 
		[
			'goroskops'			=> $goroskops,
			'zodiak_text'		=> $zodiakText,
			'goroskopsTitle'	=> $goroskopsTitle,
			'goroskops_type'	=> $goroskopsType
		]);

	}
}