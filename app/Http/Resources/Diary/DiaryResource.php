<?php

namespace App\Http\Resources\Diary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Profile\ProfileShort2Resource;

class DiaryResource extends JsonResource
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
			'title'			 	=> $this->title,
			'user_id'		 	=> $this->user_id,
			'description'	 	=> $this->description,
			'description_brief'	=> $this->description_brief,
			'picture'		 	=> $this->picture,
			'picture_url'	 	=> $this->picture_url,
			'create_time'	 	=> $this->create_time,
			'user'				=> new ProfileShort2Resource($this->user),
			'name_class'		=> $this->name_class,
			'comments_count'	=> $this->comments_count
		];
	}
}
