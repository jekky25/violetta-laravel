<?php

namespace App\Http\Resources\City;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityShortResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'			=> $this->id,
			'name'		 	=> $this->name,
			'country_id'	=> $this->country_id,
			'region_id'	 	=> $this->region_id
		];
	}
}
