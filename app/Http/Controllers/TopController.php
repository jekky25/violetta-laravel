<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Services\FormatService;

class TopController extends Controller
{
	/**
	 * Show a top100 page
	 * @return \Illuminate\Http\Response
	 */
	public function top100(FormatService $format)
	{
		return response()->view(
			'registration.top100',
			[
				'textTop100'	=> $format->getTextTop100(),
				'formToTop'		=> $format->getFormToTop()
			]
		);
	}

	/**
	 * update top100
	 * @return void
	 */
	public function top100Post(FormatService $format)
	{
		$user 			= Auth::user();
		if (count($user->photo) == 0) {
			return redirect()->route('registration.top100')->with(
				[
					'textTop100'	=> $format->getTextTop100Update(),
					'formToTop'		=> $format->getFormToTopUpdate()
				]
			);
		}
		$this->userRepository->update($user, ['top100' => time()]);
		return redirect()->route(Route::currentRouteName())->with('success', 'Информация сохранена.');
	}
}