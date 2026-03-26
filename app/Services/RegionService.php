<?php

namespace App\Services;

use App\Interfaces\RegionInterface;
use Illuminate\Support\Collection;

class RegionService
{
	public function __construct(
		private RegionInterface $repository
	) {}

	/**
	 * get regions from the repository 
	 */
	public function getList(int $countryId): Collection
	{
		return $this->repository->getByCountryId($countryId);
	}
}
