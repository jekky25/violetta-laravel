<?php

namespace App\Services;

use App\Filters\UserFilter;
use App\Services\SearchService;
use App\Fields\SearchField;
use App\Interfaces\UserInterface;

class ProfileSearchService
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $repository,
		protected SearchService $search

	) {}

	/**
	 * get profiles over search
	 */
	public function search(array $data, UserFilter $filter, SearchField $fields): array
	{
		return [
				'fields'			=> $fields->get(),
				'critsSearch'		=> $this->search->getSearchText($data),
				'ankets'			=> $this->repository->getBySearch($filter, $data)
			];
	}
}
