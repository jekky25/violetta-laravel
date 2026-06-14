<?php

namespace App\DTO;

class PhotoCommentDTO
{
	public function __construct(
		public readonly string $description
	) {}

	public static function fromRequest(array $data): self
	{
		return new self(
			description: $data['description']
		);
	}
}