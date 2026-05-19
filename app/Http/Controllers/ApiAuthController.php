<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Profile\AuthProfileResource;
use App\Requests\LoginApiRequest;
use App\Services\ApiAuthService;

class ApiAuthController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected ApiAuthService $service) {}

	/**
	 * get User if he or she is logined
	 * @return ?AuthProfileResource
	 */
	public function me()
	{
		return Auth::user() ? new AuthProfileResource(Auth::user()) : null;
	}

	/**
	 * login
	 * @param LoginApiRequest $request
	 * @return AuthProfileResource
	 */
	public function login(LoginApiRequest $request)
	{
 		return $this->service->loginApi($request->validated());
	}
}
