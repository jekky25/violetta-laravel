<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Requests\UserBirthdayRequest;
use App\Filters\UserBirthdayFilter;
use App\Requests\UserViewsRequest;
use App\Filters\UserViewsFilter;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileFeedService
{
	/**
	* Create a new service instance.
	*
	* @return void
	*/
	public function __construct(private UserInterface $repository) {}

	/**
	 * get birthay profiles
	 */
	public function getBirthday(UserBirthdayFilter $filter, UserBirthdayRequest $request): LengthAwarePaginator
	{
		return $this->repository->getBySearch($filter, $request);
	}

	/**
	 * get profiles who visited your profile the last 30 days
	 */
	public function getViews(UserViewsFilter $filter, UserViewsRequest $request, User $user): LengthAwarePaginator
	{
		$this->repository->update($user, ['lastvisit_views' => time()]);
		return $this->repository->getBySearch($filter, $request);
	}
}