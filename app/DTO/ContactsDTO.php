<?

namespace App\DTO;

class ContactsDTO
{
	public function __construct(
		public readonly string $name,
		public readonly string $organization,
		public readonly string $email,
		public readonly string $description
	) {}

	public static function fromRequest($request): self
	{
		return new self(
			name: $request->name,
			organization: $request->organization,
			email: $request->email,
			description: $request->description
		);
	}

	public function toArray(): array
    {
        return [
			'name' => $this->name,
			'organization' => $this->organization,
			'email' => $this->email,
			'description' => $this->description
        ];
    }

}