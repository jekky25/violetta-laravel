<?php

namespace App\DTO;

class ForgetPasswordDTO
{
	public function __construct(
		public readonly string $email,
	) {}

	public static function fromRequest($request): self
	{
		return new self(
			email: $request->email
		);
	}

	public function toArray(): array
	{
		return [
			'email' => $this->email
		];
	}

	public function getEmail(): string
	{
		return $this->email;
	}
}
