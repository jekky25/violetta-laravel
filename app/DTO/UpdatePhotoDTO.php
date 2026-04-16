<?

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class UpdatePhotoDTO
{
	public function __construct(
		public readonly UploadedFile $photo
	) {}

	public static function fromRequest($request): self
	{
		return new self(
			photo: $request->photo
		);
	}
}