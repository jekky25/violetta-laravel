<?php

namespace App\Services;

use App\DTO\RegistrationProfileDTO;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationEmail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegistrationService
{
	public function __construct(protected UserInterface $repository) {}

	
	/**
	 * Create a new user and send him an email
	 */
	public function store(RegistrationProfileDTO $dto): User
	{
		$data = $dto->toArray();
		$data['hash'] = Hash::make($data['password']);
		$user = $this->repository->create($data);
		Mail::mailer(config('mail.mail_mode'))
			->to($user->email)
			->send(new RegistrationEmail($user));
		return $user;
	}
}
