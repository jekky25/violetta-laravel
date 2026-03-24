<?

namespace App\DTO;

class DreamBookDTO
{
	public function __construct(
		public int $id,
		public string $name,
		public string $description
	) {
		$this->description = preg_replace('/sonnik_id([0-9]+).html/i', 'dreambook/$1.html',	$this->description);
	}
}
