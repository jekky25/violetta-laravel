<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Fields\SettingsField;
use App\Requests\SettingRequest;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected SettingsService $service) {}

	/**
	 * Show a setting page
	 * @return \Illuminate\Http\Response
	 */
	public function index(SettingsField $fields)
	{
		return response()->view('registration.settings', ['fields'	=> $fields]);
	}

	/**
	 * Update user settings
	 * @param SettingRequest $request
	 * @return RedirectResponse
	 */
	public function update(SettingRequest $request)
	{
		$this->service->update(request()->user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}