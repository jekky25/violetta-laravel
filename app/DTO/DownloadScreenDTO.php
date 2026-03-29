<?php

namespace App\DTO;

class DownloadScreenDTO
{
	public function __construct(
		public readonly int $screenId,
		public readonly int $type,
	) {}
}
