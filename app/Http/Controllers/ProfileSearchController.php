<?php

namespace App\Http\Controllers;

use App\Requests\SearchRequest;
use App\Filters\UserFilter;
use App\Services\SearchService;
use App\Fields\SearchField;
use App\Interfaces\UserInterface;
use App\Services\AnkService;

class ProfileSearchController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		protected UserInterface $userRepository
	) {}

	/**
	 * show the page with profiles by filter
	 * @param  SearchRequest  $request
	 * @param  UserFilter  $filter
	 * @param  SearchService  $search
	 * @param  SearchField $fields
	 * @return \Illuminate\Http\Response
	 */
	public function search(SearchRequest $request, UserFilter $filter, SearchService $search, SearchField $fields): \Illuminate\Http\Response
	{
		$ankets				= $this->userRepository->getBySearch($filter, $request);
		$critsSearch		= $search->getSearchText($ankets, $request->validated());
		$countSearchAnkStr	= (new AnkService($ankets))->getFoundStr();
		return response()->view(
			'ankets.search',
			[
				'fields'			=> $fields->get(),
				'isSend'			=> isset($request->send) ? 'Y' : 'N',
				'critsSearch'		=> $critsSearch,
				'ankets'			=> $ankets,
				'countSearchAnkStr'	=> $countSearchAnkStr
			]
		);
	}
}
