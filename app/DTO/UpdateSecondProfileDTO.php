<?

namespace App\DTO;

class UpdateSecondProfileDTO
{
	public function __construct(
		public readonly int $sex_orient,
		public readonly array $targets,
		public readonly array $speak_lang,
		public readonly int $body,
		public readonly int $height,
		public readonly int $weight,
		public readonly int $hair_color,
		public readonly int $hair_type,
		public readonly int $eyes,
		public readonly int $education,
		public readonly int $smoke,
		public readonly int $alcohol,
		public readonly int $family_status,
		public readonly int $children,
		public readonly int $help_money,
		public readonly array $interests,
		public readonly ?string $icq,
		public readonly ?string $url,
		public readonly ?string $phone,
		public readonly ?string $description

	) {}

	public static function fromRequest($request): self
	{
		return new self(
			sex_orient: $request->sex_orient,
			targets: $request->targets,
			speak_lang: $request->speak_lang,
			body: $request->body,
			height: $request->height,
			weight: $request->weight,
			hair_color: $request->hair_color,
			hair_type: $request->hair_type,
			eyes: $request->eyes,
			education: $request->education,
			smoke: $request->smoke,
			alcohol: $request->alcohol,
			family_status: $request->family_status,
			children: $request->children,
			help_money: $request->help_money,
			interests: $request->interests,
			icq: $request->icq,
			url: $request->url,
			phone: $request->phone,
			description: $request->description
		);
	}

	public function toArray(): array
    {
        return [
			'sex_orient' => $this->sex_orient,
			'targets' => $this->targets,
			'speak_lang' => $this->speak_lang,
			'body' => $this->body,
			'height' => $this->height,
			'weight' => $this->weight,
			'hair_color' => $this->hair_color,
			'hair_type' => $this->hair_type,
			'eyes' => $this->eyes,
			'education' => $this->education,
			'smoke' => $this->smoke,
			'alcohol' => $this->alcohol,
			'family_status' => $this->family_status,
			'children' => $this->children,
			'help_money' => $this->help_money,
			'interests' => $this->interests,
			'icq' => $this->icq,
			'url' => $this->url,
			'phone' => $this->phone,
			'description' => $this->description
        ];
    }
}