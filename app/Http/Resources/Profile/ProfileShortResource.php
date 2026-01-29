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
			'id'				=> $this->id,
			'name'			 	=> $this->name,
			'sex'		 		=> $this->sex,
			'age'			 	=> $this->age,
			'age_type'	 		=> $this->age_type,
			'city'				=> new CityShortResource($this->city),
			'photo'				=> new PhotoShortResource($this->photo),
			'photos_count'		=> $this->photos_count,
			'find_sex_orient'	=> $this->find_sex_orient
		];
	}
}
