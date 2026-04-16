<?php

namespace App\Http\Resources\Photo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoShortResource extends JsonResource
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
			'user_id'	 	=> $this->user_id,
			'main_mpicture'	=> $this->main_mpicture,
			'create_time'	=> $this->create_time,
			'url'			=> $this->url
		];
	}
}
