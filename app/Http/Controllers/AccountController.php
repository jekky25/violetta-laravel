<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $repository) {}

	/**
	 * Delete profile
	 * @return RedirectResponse
	 */
	public function destroy(): RedirectResponse
	{
		$user = request()->user();
		Auth::logout();
		$this->repository->destroy($user);
		return redirect()->route('home')->with('success', 'Ваша анкета удалена. Но мы надеемся, что Вы еще вернетесь.');
	}
}