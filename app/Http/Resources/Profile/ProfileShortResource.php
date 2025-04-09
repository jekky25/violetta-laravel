<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use App\Http\Resources\City\CityShortResource;
use App\Http\Resources\Photo\PhotoShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileShortResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'			=> $this->user_id,
			'name'		 	=> $this->user_name,
			'age'		 	=> $this->user_age,
			'age_type'	 	=> $this->user_age_type,
			'city'			=> new CityShortResource($this->city),
			'photo'			=> new PhotoShortResource($this->photo)
		];
	}
}
