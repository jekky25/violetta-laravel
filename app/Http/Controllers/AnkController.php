<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AnketEvaluationInterface;
use App\Interfaces\AnketVisitInterface;
use App\Interfaces\PhotoInterface;
use App\Interfaces\UserInterface;
use App\Services\PhotoService;
use App\Services\AnkService;

class AnkController extends Controller
{
	const IS_MAIN_PHOTO					= 1;
	public static $visitDays 			= 30;
	public $commentCountPerPage 		= 100;
	
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(
		protected AnketEvaluationInterface $anketEvaluationRepository,
		protected AnketVisitInterface $anketVisitRepository,
		protected PhotoInterface $photoRepository,
		protected UserInterface $userRepository,
		protected PhotoService $photoService
	)
	{
	}

	/**
	* Show a profile page
	* @param  Request  $request
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getAnk(Request $request, $id)
	{
		$user				= Auth::user();
		$mode				= Route::currentRouteName() == 'ank.full.id' ? 'full' : '';
		$anket 				= $this->userRepository->getById($id);
		$this->ankService	= new AnkService($anket);
		$this->ankService->prepare();

		//making an ankets review and a count of views
		if (!empty ($user))
		{
			$anket->ankVisits	= $this->anketVisitRepository->update($id, self::$visitDays, $user->user_id);

			$this->anketEvaluationRepository->getEvaluations($user->user_id, $id);
			$ankEvaluationed = $this->anketEvaluationRepository->getEvaluationWithUpdate($request, $user->user_id, $id);
			if (is_object($ankEvaluationed) && get_class($ankEvaluationed) == 'Illuminate\Http\RedirectResponse') return $ankEvaluationed;
		}
		//making a full anket
		if ($mode == 'full') {
			$this->ankService->prepareFull();
			$isAboutPartner = $this->ankService->isAboutPartner();
		}
		return response()->view('ankets.page',
		[
			'userData'			=> $anket,
			'ankEvaluationed' 	=> isset($ankEvaluationed)	? $ankEvaluationed	: false,
			'isAboutPartner' 	=> isset($isAboutPartner)	? $isAboutPartner	: false
		]);
	}

	/**
	* Show a page with user pictures
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getMainPhoto($id)
	{
		$anket	= $this->userRepository->getById($id);
		if (!count($anket->photo)) abort(404);
		foreach ($anket->photo as $photo)
		{
			if ($photo->main_picture == static::IS_MAIN_PHOTO) return redirect()->route('ank.photo.photo_id', $photo->id);
		}
		abort(404);
	}

	/**
	* Show a page with user pictures
	* @param  int $id
	* @return \Illuminate\Http\Response
	*/
	public function getPhoto($id)
	{
		$user				= Auth::user();
		$user				= !empty($user) ? $user->load(['visits']) : null;
		$photo	= $this->photoRepository->getById($id);
		$anket	= $this->userRepository->getById($photo->user_id);
		if (!count($anket->photo)) abort(404);
		$anket->ankVisits	= $this->anketVisitRepository->update($photo->user_id, self::$visitDays, $user->user_id);
		$this->photoService->prepare($anket, $photo->id, $this->commentCountPerPage);
		return response()->view('ankets.photo',
		[
			'userData'			=> $anket,
		]);
	}
}