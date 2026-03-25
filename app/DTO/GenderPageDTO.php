<?php

namespace App\DTO;

class GenderPageDTO
{
	public function __construct(
		public string $sex,
		public array $alphabet,
		public $names,
		public string $title
	) {}
}
