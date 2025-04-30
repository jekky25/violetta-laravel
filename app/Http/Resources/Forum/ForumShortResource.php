<?php

namespace App\Http\Resources\Forum;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumShortResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'			=> $this->topic_id,
			'forum_id'		=> $this->forum_id,
			'title'		 	=> \Illuminate\Support\Str::limit($this->topic_title, 28, $end = '...')
		];
	}
}
