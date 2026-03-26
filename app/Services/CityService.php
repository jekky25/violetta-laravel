<?php

namespace App\Services;

use App\Interfaces\CityInterface;
use Illuminate\Support\Collection;

class CityService
{
	public function __construct(
		private CityInterface $repository
	) {}

	/**
	 * get cities from the repository 
	 */
	public function getList(int $regionId): Collection
	{
		return $this->repository->getByRegionId($regionId);
	}
}
