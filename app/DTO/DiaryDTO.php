<?

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class DiaryDTO
{
	public function __construct(
		public readonly string $title,
		public readonly string $description,
		public readonly ?UploadedFile $photo
	) {}

	public static function fromRequest(array $data): self
	{
		return new self(
			title: $data['title'],
			description: $data['description'],
			photo: $data['photo'] ?? null
		);
	}
}