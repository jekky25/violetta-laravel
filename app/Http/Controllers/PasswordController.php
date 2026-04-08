<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Requests\PassRequest;
use Illuminate\Http\RedirectResponse;
use App\Services\PasswordService;

class PasswordController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected PasswordService $service) {}

	/**
	 * Show a change password page
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return response()->view('registration.pass');
	}

	/**
	 * Change user password
	 * @param  PassRequest $request
	 * @return RedirectResponse
	 */
	public function update(PassRequest $request)
	{
		$this->service->update(request()->user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}