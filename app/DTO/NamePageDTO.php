<?php

namespace App\DTO;

class NamePageDTO
{
	public function __construct(
		public object $name,
		public string $title,
		public array $alphabet,
		public string $text,
		public string $sex,
		public string $genderTitle
	) {}
}
