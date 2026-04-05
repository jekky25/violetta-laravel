<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;

class AccountController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $userRepository) {}

	/**
	 * Show a delete profile page
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		return response()->view('registration.delete');
	}

	/**
	 * Delete profile
	 * @return void
	 */
	public function destroyConfirm()
	{
		$this->userRepository->destroy(Auth::user());
		return redirect()->route('registration.delete')->with('success', 'Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.');
	}
}