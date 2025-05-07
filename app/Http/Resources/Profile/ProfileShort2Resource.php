<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileShort2Resource extends JsonResource
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
			'photos_count'		=> $this->photos_count,
			'find_sex_orient'	=> $this->find_sex_orient
		];
	}
}
