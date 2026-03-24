<?

namespace App\DTO;

class DreamBookDTO
{
	public function __construct(
		public int $id,
		public string $name,
		public string $description
	) {}
}
