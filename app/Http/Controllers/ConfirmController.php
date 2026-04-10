<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ConfirmService;

class ConfirmController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct(protected ConfirmService $service) {}

	/**
	 * show confirm registration page and update data
	 * @param int $userId
	 * @param string $code
	 * @return \Illuminate\Http\Response
	 */
	public function confirm($userId, $code)
	{
		return response()->view(
			'registration.confirm',
			[
				'confirmed'			=> $this->service->confirmEmail($userId, $code)
			]
		);
	}
}