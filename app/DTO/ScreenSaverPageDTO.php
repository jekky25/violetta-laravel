<?php

namespace App\DTO;

use App\Models\Screen;
use Illuminate\Support\Collection;

class ScreenSaverPageDTO
{
	public function __construct(
		public Screen $screen,
		public Collection $comments
	) {}
}
