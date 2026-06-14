<?php

namespace App\DTO;

class UpdateMainProfileDTO
{
	public function __construct(
		public readonly string $name,
		public readonly int $sex,
		public readonly int $city_id,
		public readonly int $country_id,
		public readonly int $region_id,
		public readonly string $birth_date
	) {}

	public static function fromRequest($request): self
	{
		return new self(
			name: $request->name,
			sex: $request->sex,
			city_id: $request->city_id,
			country_id: $request->country_id,
			region_id: $request->region_id,
			birth_date: $request->birth_date
		);
	}

	public function toArray(): array
    {
        return [
			'name' => $this->name,
			'sex' => $this->sex,
			'city_id' => $this->city_id,
			'country_id' => $this->country_id,
			'region_id' => $this->region_id,
			'birth_date' => $this->birth_date
        ];
    }
}