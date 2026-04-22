<?php

namespace App\Services;

use App\Interfaces\UserInterface;
use App\Requests\UserBestRequest;
use App\Filters\UserBestFilter;
use App\Requests\UserPopularRequest;
use App\Filters\UserPopularFilter;
use App\Requests\AnketsRequest;
use App\Filters\AnketsFilter;
use App\Enums\AgeRange;
use App\Models\User;

class ProfileBrowseService
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $repository
	) {}

	/**
	 * get profiles from the repository
	 */
	public function getList(AnketsRequest $request, AnketsFilter $filter, ?string $sex, ?string $age): array
	{
		return [
				'popSex'			=> AgeRange::title($sex, $age),
				'sex'				=> $sex,
				'ankets'			=> empty($sex) && empty($age) ? $this->repository->newFaces(config('pagination.profiles_under_menu'))
					                                             : $this->repository->getBySearch($filter, $request)
			];
	}

	/**
	 * get top100 profiles
	 */
	public function getTop100(int $sex, int $perPage): User
	{
		return $this->repository->getTop100($sex, $perPage);
	}

	/**
	 * get the most popular profiles
	 */
	public function getPopular(UserPopularRequest $request, UserPopularFilter $filter, string $sex): array
	{
		return [
			'ankets'		=> $this->repository->getBySearch($filter, $request),
			'popSex'		=> $sex == 'men' ? 'мужчины' : 'женщины'
		];
	}

	/**
	 * get the best profile from the women or men profiles
	 */
	public function getBest(UserBestRequest $request, UserBestFilter $filter, string $sex, ?User $user): array
	{
		return [
			'ankets'			=> $this->repository->getBySearch($filter, $request, 'top100'),
			'titleId'			=> $sex == 'men' ? 'Лучшие парни' : 'Лучшие девушки',
			'user'				=> $user
		];
	}
}
