<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Fields\ProfileEditField;
use App\Requests\ProfileMainRequest;
use App\Interfaces\UserInterface;
use App\Fields\ProfileSecondField;
use App\Requests\ProfileSecondRequest;
use App\Fields\ProfilePartnerField;
use App\Requests\ProfilePartnerRequest;

class ProfileController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $userRepository) {}

	/**
	 * Show an edit short profile page
	 * @return \Illuminate\Http\Response
	 */
	public function edit(ProfileEditField $fields)
	{
		return response()->view('registration.edit', ['fields'	=> $fields]);
	}

	/**
	 * Edit a short user profile
	 * @param  ProfileMainRequest $request
	 * @return void
	 */
	public function post(ProfileMainRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show an edit full profile page
	 * @return \Illuminate\Http\Response
	 */
	public function second(ProfileSecondField $fields)
	{
		return response()->view('registration.second', ['fields' => $fields]);
	}

	/**
	 * Edit a full user profile
	 * @param ProfileSecondRequest $request
	 * @return void
	 */
	public function secondPost(ProfileSecondRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}

	/**
	 * Show an edit partner page
	 * @return \Illuminate\Http\Response
	 */
	public function partner(ProfilePartnerField $fields)
	{
		return response()->view('registration.partner', ['fields' => $fields]);
	}

	/**
	 * Edit a partner profile
	 * @param  ProfilePartnerRequest $request
	 * @return void
	 */
	public function partnerPost(ProfilePartnerRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}