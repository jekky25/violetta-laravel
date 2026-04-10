<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Requests\ForgetPasswordRequest;
use App\Services\ForgetPasswordService;
use App\DTO\ForgetPasswordDTO;
class ForgetPasswordController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(protected ForgetPasswordService $service) {}

	/**
	 * show a forget password page
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return response()->view('registration.forget_pass');
	}

	/**
	 * send email from the forget password page
	 * @param  ForgetPasswordRequest $request
	 * @return void
	 */
	public function sendPassword(ForgetPasswordRequest $request)
	{
		$dto = ForgetPasswordDTO::fromRequest($request);
		$this->service->sendPassword($dto);
		return redirect()->route(Route::currentRouteName())->with('success', "<p>На адрес {$dto->getEmail()} было выслано письмо с вашим паролем!");
	}
}
