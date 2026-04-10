<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Requests\RegistrationRequest;
use App\Fields\RegistrationField;
use App\DTO\RegistrationProfileDTO;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(protected RegistrationService $service) {}

	/**
	 * show a registration page
	 * @return \Illuminate\Http\Response
	 */
	public function create(RegistrationField $fields)
	{
		return response()->view(
			session('success') ? 'registration.finish' :
								 'registration.registration',
								 ['fields' => $fields]);
	}

	/**
	 * post data after registration and send e-mail with registration information
	 * @param  RegistrationRequest $request
	 * @return void
	 */
	public function store(RegistrationRequest $request)
	{
		$user = $this->service->store(RegistrationProfileDTO::fromRequest($request));
		Auth::login($user, true);
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}