<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\DreamBookInterface;
use App\Traits\Tstr;

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
	public function __construct(
		protected DreamBookInterface $dreamBookRepository
	)
	{
	}

	/**
	* Show the page with dreambooks
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function index($id = 1)
	{
		$dreamBookLiterals		= $this->dreamBookRepository->getLiter();
		$op						= !empty ($id) ? $id : $this->op;
		$words					= $this->dreamBookRepository->get($this->countPerPage, $op);
		$page					= $words->currentPage();
		return response()->view ('dreambooks', 
		[
			'dreamBookLiterals'		=> $dreamBookLiterals,
			'words'					=> $words,
			'page'					=> $page
		]);
	}

	/**
	* Show the page with a dreambook
	* @param  \Illuminate\Http\Request  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getItem($id)
	{
		$dreamBookLiterals		= $this->dreamBookRepository->getLiter();
		$dreambook 				= $this->dreamBookRepository->getById($id);
		$dreambook->description = $this->replaceStringByPattern ($dreambook->description, $this->pattern, $this->replacement);

		return response()->view ('dreambooks_id', 
		[
			'dreamBookLiterals'		=> $dreamBookLiterals,
			'dreambook'				=> $dreambook
		]);
	}
}