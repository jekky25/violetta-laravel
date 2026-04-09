<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Services\Top100Service;
use Illuminate\Http\RedirectResponse;

class Top100Controller extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected Top100Service $service) {}

	/**
	 * Show a top100 page
	 * @return RedirectResponse
	 */
	public function show()
	{
		return response()->view('registration.top100');
	}

	/**
	 * update top100
	 * @return RedirectResponse
	 */
	public function update()
	{
		$params = $this->service->update(request()->user());
		return redirect()->route(Route::currentRouteName())->with($params);
	}
}