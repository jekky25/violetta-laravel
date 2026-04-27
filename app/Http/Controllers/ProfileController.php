<?php

namespace App\Http\Controllers;

use App\DTO\UpdateMainProfileDTO;
use App\DTO\UpdateSecondProfileDTO;
use App\DTO\UpdatePartnerProfileDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Fields\ProfileEditField;
use App\Requests\ProfileMainRequest;
use App\Fields\ProfileSecondField;
use App\Requests\ProfileSecondRequest;
use App\Fields\ProfilePartnerField;
use App\Requests\ProfilePartnerRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected ProfileService $service) {}

	/**
	 * Show a profile page
	 * @param  Request  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */

	public function show(Request $request, int $id)
	{
		return $this->handle($request, $id, 'short');
	}

	/**
	 * Show a full profile page
	 * @param  Request  $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */	
	public function showFull(Request $request, int $id)
	{
		return $this->handle($request, $id, 'full');
	}

	/**
	 * hangle (show a page and a full page)
	 * @param  Request  $request
	 * @param  int $id
	 * @param  string $mode
	 * @return \Illuminate\Http\Response
	 */	
	private function handle(Request $request, int $id, string $mode)
	{
		return view('ankets.page', $this->service->show($request, $id, $mode));
	}

	/**
	 * Show an edit short profile page
	 * @return \Illuminate\Http\Response
	 */
	public function editMain(ProfileEditField $fields)
	{
		return response()->view('registration.edit', ['fields'	=> $fields]);
	}

	/**
	 * Edit a short user profile
	 * @param  ProfileMainRequest $request
	 * @return RedirectResponse
	 */
	public function updateMain(ProfileMainRequest $request)
	{
		$this->service->updateMain(Auth::user(), UpdateMainProfileDTO::fromRequest($request));
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show an edit full profile page
	 * @return \Illuminate\Http\Response
	 */
	public function editSecond(ProfileSecondField $fields)
	{
		return response()->view('registration.second', ['fields' => $fields]);
	}

	/**
	 * Edit a full user profile
	 * @param ProfileSecondRequest $request
	 * @return RedirectResponse
	 */
	public function updateSecond(ProfileSecondRequest $request)
	{
		$this->service->updateSecond(Auth::user(), UpdateSecondProfileDTO::fromRequest($request));
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show an edit partner page
	 * @return \Illuminate\Http\Response
	 */
	public function editPartner(ProfilePartnerField $fields)
	{
		return response()->view('registration.partner', ['fields' => $fields]);
	}

	/**
	 * Edit a partner profile
	 * @param  ProfilePartnerRequest $request
	 * @return RedirectResponse
	 */
	public function updatePartner(ProfilePartnerRequest $request)
	{
		$this->service->updatePartner(Auth::user(), UpdatePartnerProfileDTO::fromRequest($request));
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}