<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Profile\AuthProfileResource;
use App\Requests\LoginApiRequest;
use App\Interfaces\UserInterface;
use App\Requests\LoginRequest;

class AuthController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(protected UserInterface $userRepository)
	{
		$this->middleware('web');
	}

	/**
	 * get User if he or she is logined
	 * @return bool
	 */
	public function getAuth()
	{
		$user = Auth::user();
		if (empty($user)) return null;
		return new AuthProfileResource($user);
	}

	/**
	 * login
	 * @param LoginRequest $request
	 * @return void
	 */
	public function loginApi(LoginApiRequest $request)
	{
		$arParams		= $request->validated();
		$remember		= true;
		$user			= $this->userRepository->getByLoginAndPass($arParams['login'], $arParams['password']);
		if (empty($user)) {
			$errors =
				[
					'message'	=> LoginApiRequest::$userNotFounded,
					'errors'	=> ['user' => [LoginApiRequest::$userNotFounded]]
				];
			return response($errors, 400);
		} else {
			Auth::login($user, $remember);
			$user = $this->userRepository->getById($user->id);
			return new AuthProfileResource($user);
		}
	}

	/**
	 * logout
	 * @return void
	 */
	public function logout()
	{
		Auth::logout();
		return redirect()->route('home');
	}

	/**
	 * login
	 * @param LoginRequest $request
	 * @return void
	 */
	public function login(LoginRequest $request)
	{
		$arParams		= $request->validated();
		$remember		= true;
		$user			= $this->userRepository->getByLoginAndPass($arParams['username_template'], $arParams['pass_template']);
		if (empty($user)) {
			$title			= 'Информация';
			$text			= 'Неверно указаны имя пользователя или пароль!';
			$this->messageService->outMessageDie($title, $text);
		} else
			Auth::login($user, $remember);
		return redirect()->route('home');
	}
}
