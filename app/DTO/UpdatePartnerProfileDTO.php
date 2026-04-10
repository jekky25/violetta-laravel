<?

namespace App\DTO;

class UpdatePartnerProfileDTO
{
	public function __construct(
		public readonly int $partner_age_min,
		public readonly int $partner_age_max,
		public readonly int $partner_height_min,
		public readonly int $partner_height_max,
		public readonly int $partner_weight_min,
		public readonly int $partner_weight_max,
		public readonly array $partner_body,
		public readonly array $partner_languages,
		public readonly array $partner_alcohol,
		public readonly array $partner_smoke,
		public readonly array $partner_education,
		public readonly ?int $partner_country,
		public readonly ?int $partner_region,
		public readonly ?int $partner_city,
		public readonly string $partner_description
	) {}

	public static function fromRequest($request): self
	{
		return new self(
			partner_age_min: $request->partner_age_min,
			partner_age_max: $request->partner_age_max,
			partner_height_min: $request->partner_height_min,
			partner_height_max: $request->partner_height_max,
			partner_weight_min: $request->partner_weight_min,
			partner_weight_max: $request->partner_weight_max,
			partner_body: $request->partner_body,
			partner_languages: $request->partner_languages,
			partner_alcohol: $request->partner_alcohol,
			partner_smoke: $request->partner_smoke,
			partner_education: $request->partner_education,
			partner_country: $request->partner_country,
			partner_region: $request->partner_region,
			partner_city: $request->partner_city,
			partner_description: $request->partner_description
		);
	}

	public function toArray(): array
    {
        return [
			'partner_age_min' => $this->partner_age_min,
			'partner_age_max' => $this->partner_age_max,
			'partner_height_min' => $this->partner_height_min,
			'partner_height_max' => $this->partner_height_max,
			'partner_weight_min' => $this->partner_weight_min,
			'partner_weight_max' => $this->partner_weight_max,
			'partner_body' => $this->partner_body,
			'partner_languages' => $this->partner_languages,
			'partner_alcohol' => $this->partner_alcohol,
			'partner_smoke' => $this->partner_smoke,
			'partner_education' => $this->partner_education,
			'partner_country' => $this->partner_country,
			'partner_region' => $this->partner_region,
			'partner_city' => $this->partner_city,
			'partner_description' => $this->partner_description
        ];
    }
}