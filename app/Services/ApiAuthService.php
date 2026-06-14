<?php

namespace App\Services;

use App\Http\Resources\Profile\AuthProfileResource;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use App\Requests\LoginApiRequest;

class ApiAuthService
{
	public function __construct(
		private UserInterface $repository
	) {}

	/**
	 * login user over api
	 * 
	 */
	public function loginApi(array $data): AuthProfileResource|Response
	{
		$user = $this->repository->getByLoginAndPass($data['login'], $data['password']);
		if ($user !== null) {
			Auth::login($user, true);
			return new AuthProfileResource($user);
		}
		
		$errors = [
			'message'	=> LoginApiRequest::$userNotFounded,
			'errors'	=> ['user' => [LoginApiRequest::$userNotFounded]]
		];
		return response($errors, 400);
	}
}