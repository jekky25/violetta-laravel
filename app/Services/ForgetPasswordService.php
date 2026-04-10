<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\DTO\ForgetPasswordDTO;
use App\Mail\ForgetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(private UserInterface $repository) {}

	/**
	* Send password to the User
	*/
	public function sendPassword(ForgetPasswordDTO $dto): void
	{
		$email	= $dto->getEmail();
		$user	= $this->repository->getByEmail($email);
		if (!empty($user)) {
			Mail::mailer(config('mail.mail_mode'))
				->to($email)
				->send(new ForgetPasswordEmail($user));
		}
	}
}