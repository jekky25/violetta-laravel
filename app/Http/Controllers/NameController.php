<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NameService;
use Illuminate\View\View;

class NameController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected NameService $service
	) {}

	/**
	* show the names page
	* @return View
	*/
	public function index(): View
	{
		return view('names', 
		[
			'alphabet'	=> $this->service->getAlphabet(),
			'names'		=> $this->service->getGroupedNames()
		]);
    }

	/**
	* Show genderType page
	* @param string $sex
	* @param int $id
	* @return View
	*/
	public function getGender(string $sex, int $id = 1): View
	{
		return view('names.gender', $this->service->getGenderPageData($sex, $id));
	}

	/**
	* Show the name page by id
	* @param int $id
	* @return View
	*/
	public function show(int $id): View
	{
		return view('names.id', $this->service->getNamePageData($id));
	}
}