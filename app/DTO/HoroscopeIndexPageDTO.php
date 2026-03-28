<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class HoroscopeIndexPageDTO
{
	public function __construct(
		public Collection $horoscopes,
		public string $zodiak_text,
		public string $horoscope_title,
		public Collection $horoscopes_type
	) {}
}