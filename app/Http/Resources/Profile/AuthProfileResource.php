<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource
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
			'name'		 		=> $this->name,
			'monthVisits'		=> $this->monthVisits,
			'monthVisitsNew'	=> $this->monthVisitsNew,
			'new_messages'		=> $this->new_messages,
			'lastvisit_format'	=> $this->lastvisit_format,
			'top100'			=> $this->top100,
			'photos_count'		=> $this->photos_count
		];
	}
}
