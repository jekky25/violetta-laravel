<?php

namespace App\Services;

use App\Interfaces\ForumInterface;

class ForumService
{
	public function __construct(
		protected ForumInterface $repository
	) {}

	/**
	 * get a top block with the latest topics
	 */
	public function getTop(): array
	{
		return $this->repository->getTop();
	}
}