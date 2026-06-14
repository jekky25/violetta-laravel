<?php

namespace App\DTO;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class DiaryCommentDTO
{
	public function __construct(
		public readonly int $diaryId,
		public readonly int $userId,
		public readonly string $title,
		public readonly string $description,
		public readonly ?UploadedFile $photo
	) {}

	public static function fromRequest(int $diaryId, User $user, array $data): self
	{
		return new self(
			diaryId: $diaryId,
			userId: $user->id,
			title: $data['title'],
			description: $data['description'],
			photo: $data['photo'] ?? null
		);
	}
}