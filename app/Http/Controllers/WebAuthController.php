<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\WebAuthService;
use App\Requests\LoginRequest;

class WebAuthController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected WebAuthService $service) {}

	/**
	 * logout
	 * @return RedirectResponse
	 */
	public function logout()
	{
		auth()->logout();
		return redirect()->route('home');
	}

	/**
	 * login
	 * @param LoginRequest $request
	 * @return RedirectResponse
	 */
	public function login(LoginRequest $request)
	{
		return $this->service->loginApi($request->validated());
	}
}
