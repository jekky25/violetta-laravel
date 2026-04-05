<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Requests\PassRequest;
use App\Interfaces\UserInterface;

class PasswordController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $userRepository) {}

	/**
	 * Show a change password page
	 * @return \Illuminate\Http\Response
	 */
	public function pass()
	{
		return response()->view('registration.pass');
	}

	/**
	 * Change user password
	 * @param  PassRequest $request
	 * @return void
	 */
	public function passPost(PassRequest $request)
	{
		$this->userRepository->update(Auth::user(), $request->validated());
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}