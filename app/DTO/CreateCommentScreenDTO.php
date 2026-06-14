<?php

namespace App\DTO;

use App\Models\User;

class CreateCommentScreenDTO
{
	public function __construct(
		public readonly int $screenId,
		public readonly int $userId,
		public readonly string $description,
		public readonly string $name,
		public readonly string $email,
	) {}

	public static function fromRequest(int $screenId, User $user, array $data): self
	{
		return new self(
			screenId: $screenId,
			userId: $user->id,
			description: $data['description'],
			name: $user->name,
			email: $user->email,
		);
	}
}