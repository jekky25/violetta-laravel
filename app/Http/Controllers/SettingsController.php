<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Fields\SettingsField;
use App\Requests\SettingRequest;
use App\Interfaces\UserInterface;

class SettingsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $userRepository) {}

	/**
	 * Show a setting page
	 * @return \Illuminate\Http\Response
	 */
	public function settings(SettingsField $fields)
	{
		return response()->view('registration.settings', ['fields'	=> $fields]);
	}

	/**
	 * Update user settings
	 * @param SettingRequest $request
	 * @return void
	 */
	public function settingsPost(SettingRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}